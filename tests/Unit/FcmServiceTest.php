<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\FcmService;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FcmServiceTest extends TestCase
{
    use RefreshDatabase;

    // simple subclass to avoid performing OAuth call
    protected function makeService(Client $client)
    {
        return new class($client) extends FcmService {
            public function __construct($client)
            {
                parent::__construct($client);
            }

            protected function getAccessToken()
            {
                return 'fake-token';
            }
        };
    }

    public function test_invalid_token_is_cleared_when_fcm_returns_not_registered()
    {
        $user = User::factory()->create(['fcm_token' => 'bad-token']);

        // mock Guzzle to throw a ClientException with NotRegistered in body
        $mock = new MockHandler([
            new ClientException(
                'Bad Request',
                new Request('POST', 'https://fcm.googleapis.com'),
                new Response(400, [], json_encode(['error' => ['message' => 'NotRegistered']])))
        ]);

        $client = new Client(['handler' => HandlerStack::create($mock)]);
        $service = $this->makeService($client);

        $result = $service->sendToDevice('bad-token', ['title' => 'test'], ['foo' => 'bar']);

        $this->assertEquals(0, $result['success']);
        $this->assertEquals(1, $result['failure']);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'fcm_token' => null,
        ]);
    }

    public function test_invalid_token_is_cleared_when_fcm_returns_unregistered()
    {
        $user = User::factory()->create(['fcm_token' => 'expired-token']);

        $mock = new MockHandler([
            new ClientException(
                'Not Found',
                new Request('POST', 'https://fcm.googleapis.com'),
                new Response(404, [], json_encode([
                    'error' => [
                        'code' => 404,
                        'status' => 'NOT_FOUND',
                        'details' => [
                            ['errorCode' => 'UNREGISTERED'],
                        ],
                    ],
                ])))
        ]);

        $client = new Client(['handler' => HandlerStack::create($mock)]);
        $service = $this->makeService($client);

        $result = $service->sendToDevice('expired-token', ['title' => 'test'], ['foo' => 'bar']);

        $this->assertEquals(0, $result['success']);
        $this->assertEquals(1, $result['failure']);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'fcm_token' => null,
        ]);
    }
}
