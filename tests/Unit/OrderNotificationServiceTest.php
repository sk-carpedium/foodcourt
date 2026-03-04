<?php

namespace Tests\Unit;

use App\Models\Order;
use Illuminate\Support\Str;
use App\Models\Restaurant;
use App\Models\OrderItem;
use App\Models\MenuItem;
use App\Models\User;
use App\Services\OrderNotificationService;
use App\Services\FcmService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

// simple fake that records calls
class FakeFcm extends FcmService
{
    public $sent = [];

    public function __construct()
    {
        // do not call parent constructor to avoid requiring real config
    }

    public function sendToDevice(string $deviceToken, array $notification, array $data = [])
    {
        $this->sent[] = compact('deviceToken', 'notification', 'data');
        return ['success' => 1, 'failure' => 0, 'results' => []];
    }

    public function sendToDevices(array $deviceTokens, array $notification, array $data = [])
    {
        foreach ($deviceTokens as $t) {
            $this->sendToDevice($t, $notification, $data);
        }
        return ['success' => count($deviceTokens), 'failure' => 0, 'results' => []];
    }
}

class OrderNotificationServiceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // ensure the minimal roles exist for tests
        \Spatie\Permission\Models\Role::create(['name' => 'waiter']);
        \Spatie\Permission\Models\Role::create(['name' => 'kitchen']);
    }

    protected function createRestaurant(string $name = 'Test'): Restaurant
    {
        return Restaurant::create(['name' => $name, 'slug' => Str::slug($name), 'is_active' => true]);
    }

    protected function createOrder(array $overrides = []): Order
    {
        return Order::create(array_merge([
            'restaurant_id' => null,
            'customer_name' => 'Test Customer',
            'subtotal' => 0,
            'total_amount' => 0,
            'order_number' => 'ORD-' . strtoupper(uniqid()),
        ], $overrides));
    }

    public function test_waiter_tokens_are_filtered_by_restaurant()
    {
        $r1 = Restaurant::create(['name' => 'R1', 'slug' => 'r1', 'is_active' => true]);
        $r2 = Restaurant::create(['name' => 'R2', 'slug' => 'r2', 'is_active' => true]);

        $w1 = User::factory()->create(['restaurant_id' => $r1->id, 'fcm_token' => 't1']);
        $w1->assignRole('waiter');
        $w2 = User::factory()->create(['restaurant_id' => $r2->id, 'fcm_token' => 't2']);
        $w2->assignRole('waiter');

        $order = $this->createOrder(['restaurant_id' => $r1->id]);

        $fake = new FakeFcm();
        $service = new OrderNotificationService($fake);
        $service->notifyWaiterNewOrder($order);

        $this->assertCount(1, $fake->sent);
        $this->assertEquals('t1', $fake->sent[0]['deviceToken']);
    }

    public function test_assigned_waiter_overrides_restaurant_filter()
    {
        $r1 = Restaurant::create(['name' => 'R1', 'slug' => 'r1', 'is_active' => true]);
        $r2 = Restaurant::create(['name' => 'R2', 'slug' => 'r2', 'is_active' => true]);

        $w1 = User::factory()->create(['restaurant_id' => $r1->id, 'fcm_token' => 't1']);
        $w1->assignRole('waiter');
        $w2 = User::factory()->create(['restaurant_id' => $r2->id, 'fcm_token' => 't2']);
        $w2->assignRole('waiter');

        $order = $this->createOrder(['restaurant_id' => $r1->id, 'assigned_waiter_id' => $w2->id]);

        $fake = new FakeFcm();
        $service = new OrderNotificationService($fake);
        $service->notifyWaiterNewOrder($order);

        $this->assertCount(1, $fake->sent);
        $this->assertEquals('t2', $fake->sent[0]['deviceToken']);
    }

    public function test_kitchen_notification_targets_kitchen_users()
    {
        $r1 = Restaurant::create(['name' => 'R1', 'slug' => 'r1', 'is_active' => true]);
        $k1 = User::factory()->create(['restaurant_id' => $r1->id, 'fcm_token' => 'k1']);
        $k1->assignRole('kitchen');
        $order = $this->createOrder(['restaurant_id' => $r1->id]);

        $fake = new FakeFcm();
        $service = new OrderNotificationService($fake);
        $service->notifyKitchenNewOrder($order);
        $this->assertCount(1, $fake->sent);
        $this->assertEquals('k1', $fake->sent[0]['deviceToken']);
    }

    public function test_waiter_preparing_and_ready_notifications()
    {
        $r1 = Restaurant::create(['name' => 'R1', 'slug' => 'r1', 'is_active' => true]);
        $w1 = User::factory()->create(['restaurant_id' => $r1->id, 'fcm_token' => 't1']);
        $w1->assignRole('waiter');
        $order = $this->createOrder(['restaurant_id' => $r1->id]);

        $fake = new FakeFcm();
        $service = new OrderNotificationService($fake);
        $service->notifyWaiterOrderPreparing($order);
        $service->notifyWaiterOrderReady($order);

        // two separate calls should produce two entries
        $this->assertCount(2, $fake->sent);
        $this->assertEquals('t1', $fake->sent[0]['deviceToken']);
        $this->assertEquals('t1', $fake->sent[1]['deviceToken']);
    }

    public function test_assigned_waiter_without_token_falls_back_to_restaurant()
    {
        $r1 = Restaurant::create(['name' => 'R1', 'slug' => 'r1', 'is_active' => true]);
        $w1 = User::factory()->create(['restaurant_id' => $r1->id, 'fcm_token' => 't1']);
        $w1->assignRole('waiter');
        $w2 = User::factory()->create(['restaurant_id' => $r1->id, 'fcm_token' => null]);
        $w2->assignRole('waiter');

        $order = $this->createOrder(['restaurant_id' => $r1->id, 'assigned_waiter_id' => $w2->id]);
        $fake = new FakeFcm();
        $service = new OrderNotificationService($fake);
        $service->notifyWaiterNewOrder($order);

        $this->assertCount(1, $fake->sent);
        $this->assertEquals('t1', $fake->sent[0]['deviceToken']);
    }
}
