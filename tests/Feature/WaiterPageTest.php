<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WaiterPageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $waiter = \Spatie\Permission\Models\Role::create(['name' => 'waiter']);
        \Spatie\Permission\Models\Role::create(['name' => 'kitchen']);

        // ensure permission exists and assign to waiter
        $perm = \Spatie\Permission\Models\Permission::firstOrCreate(['name' => 'view table orders']);
        $waiter->givePermissionTo($perm);
    }

    public function test_waiter_orders_page_loads_for_waiter_role()
    {
        // create a waiter user with role
        $user = User::factory()->create();
        $user->assignRole('waiter');

        // hit the page
        $response = $this->actingAs($user)->get('/waiter/orders');
        $response->assertStatus(200);
        $response->assertSee('Order Management');
    }

    public function test_guest_cannot_access_waiter_page()
    {
        $response = $this->get('/waiter/orders');
        $response->assertRedirect('/login');
    }
}
