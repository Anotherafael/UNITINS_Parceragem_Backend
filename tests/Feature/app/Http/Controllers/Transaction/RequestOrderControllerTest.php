<?php

namespace Tests\Feature\app\Http\Controllers\Transaction;

use App\Models\Auth\Professional;
use Tests\TestCase;
use App\Models\Auth\User;
use App\Models\Transaction\Order;

class RequestOrderControllerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function professional_is_not_allowed_to_make_an_order_request()
    {
        $this->refreshDatabase();

        $user = Professional::factory()->create();
        $order = Order::factory()->create();

        $payload = [
            'order_id' => $order->id,
        ];

        /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
        $response = $this->actingAs($user, 'professionals')->post(route('request_order'), $payload);
        $response->assertStatus(401);
        $response->assertJson([
            'status' => 'Error',
            'message' => 'Professional is not allowed to request orders'
        ]);
    }

    /** @test */
    public function user_is_allowed_to_make_an_order_request()
    {
        $this->refreshDatabase();

        $user = User::factory()->create();
        $order = Order::factory()->create();

        $payload = [
            'order_id' => $order->id,
        ];

        /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
        $response = $this->actingAs($user, 'users')->post(route('request_order'), $payload);
        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'Success',
            'message' => 'Request sent'
        ]);
    }
}
