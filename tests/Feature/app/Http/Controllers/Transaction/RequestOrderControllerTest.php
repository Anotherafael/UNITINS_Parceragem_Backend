<?php

namespace Tests\Feature\app\Http\Controllers\Transaction;

use Tests\TestCase;
use App\Models\Auth\User;
use App\Models\Transaction\Order;

class RequestOrderControllerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function testValidateInputs()
    {
        $code = 400;
        $this->refreshDatabase();
        $this->artisan('passport:install');

        $user = User::factory()->create();

        $payload = [
            'order_id' => 'wrong id',
        ];

        /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
        $response = $this->actingAs($user, 'users')->post(route('request_order'), $payload);
        $response->assertStatus($code);
        $response->assertJson([
            'status' => $code, 
            'success' => false, 
            'message' => 'Invalid inputs'
        ]);
    }

    public function testUserShouldRequestAnOrder()
    {
        $code = 200;
        $this->refreshDatabase();
        $this->artisan('passport:install');

        $user = User::factory()->create();
        $order = Order::factory()->create();

        $payload = [
            'order_id' => $order->id,
        ];

        /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
        $response = $this->actingAs($user, 'users')->post(route('request_order'), $payload);
        $response->assertStatus($code);
        $response->assertJson([
            'status' => $code, 
            'success' => true, 
            'message' => 'Request sent'
        ]);
    }
}
