<?php

namespace Tests\Feature\app\Http\Controllers\Transaction;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Auth\User;
use App\Models\Service\Service;
use App\Models\Transaction\Order;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RequestOrderControllerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function testUserShouldNotSendWrongInputs()
    {
        $this->refreshDatabase();
        $this->artisan('passport:install');

        $user = User::factory()->create();

        $payload = [
            'order_id' => 'wrong id',
        ];

        /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
        $response = $this->actingAs($user, 'users')->post(route('request_order'), $payload);
        $response->assertStatus(400);
        $response->assertJson(['message' => 'Invalid inputs']);
    }

    public function testUserShouldRequestAnOrder()
    {
        $this->refreshDatabase();
        $this->artisan('passport:install');

        $user = User::factory()->create();
        $order = Order::factory()->create();

        $payload = [
            'order_id' => $order->id,
        ];

        /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
        $response = $this->actingAs($user, 'users')->post(route('request_order'), $payload);
        $response->assertStatus(200);
        $response->assertJson(['message' => 'Request sent with success']);
    }
}
