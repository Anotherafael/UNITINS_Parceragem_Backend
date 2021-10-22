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

    public function testProfessionalShouldAcceptRequestOrder()
    {
        $code = 200;
        $this->refreshDatabase();
        $this->artisan('passport:install');

        $user = Professional::factory()->create();
        $request_order = RequestOrder::factory()->create();
        
        $payload = [
            'request_order_id' => $request_order->id,
        ];

        /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
        $response = $this->actingAs($user, 'professionals')->post(route('accept_order'), $payload);
        $response->assertStatus($code);
        $response->assertJson([
            'status' => $code, 
            'success' => true, 
            'message' => 'Accepted'
        ]);
    }

    public function testProfessionalShouldRejectRequestOrder()
    {
        $code = 200;
        $this->refreshDatabase();
        $this->artisan('passport:install');

        $user = Professional::factory()->create();
        $request_order = RequestOrder::factory()->create();
        
        $payload = [
            'request_order_id' => $request_order->id,
        ];

        /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
        $response = $this->actingAs($user, 'professionals')->post(route('reject_order'), $payload);
        $response->assertStatus($code);
        $response->assertJson([
            'status' => $code, 
            'success' => true, 
            'message' => 'Rejected'
        ]);
    }
}
