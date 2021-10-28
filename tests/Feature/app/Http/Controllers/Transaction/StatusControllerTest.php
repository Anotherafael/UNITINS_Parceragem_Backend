<?php

namespace Tests\Feature\app\Http\Controllers\Transaction;

use Tests\TestCase;
use App\Models\Auth\User;
use App\Models\Auth\Professional;
use App\Models\Transaction\Order;
use App\Models\Transaction\RequestOrder;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StatusControllerTest extends TestCase
{

    /** @test */
    public function user_is_not_allowed_to_accept_or_reject_an_order_request()
    {
        $this->refreshDatabase();

        $user = User::factory()->create();
        $request_order = RequestOrder::factory()->create();
        
        $payload = [
            'request_order_id' => $request_order->id,
        ];

        /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
        $response = $this->actingAs($user, 'users')->post(route('accept_order'), $payload);
        $response->assertStatus(401);
        $response->assertJson([
            'status' => 'Error',
            'message' => 'User is not allowed to accept/reject orders'
        ]);
    }

    /** @test */
    public function professional_should_accept_an_order_request()
    {
        $this->refreshDatabase();
        
        $user = Professional::factory()->create();
        $request_order = RequestOrder::factory()->create();
        
        $payload = [
            'request_order_id' => $request_order->id,
        ];
        
        /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
        $response = $this->actingAs($user, 'professionals')->post(route('accept_order'), $payload);
        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'Success',
            'message' => 'Accepted'
        ]);
    }
    
    /** @test */
    public function professional_should_reject_an_order_request()
    {
        $this->refreshDatabase();

        $user = Professional::factory()->create();
        $request_order = RequestOrder::factory()->create();
        
        $payload = [
            'request_order_id' => $request_order->id,
        ];

        /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
        $response = $this->actingAs($user, 'professionals')->post(route('reject_order'), $payload);
        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'Success',
            'message' => 'Rejected'
        ]);
    }
}
