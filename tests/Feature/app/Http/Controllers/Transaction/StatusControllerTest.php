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
        $this->refreshDatabase();
        $this->artisan('passport:install');

        $user = Professional::factory()->create();
        $request_order = RequestOrder::factory()->create();
        
        $payload = [
            'request_order_id' => $request_order->id,
        ];

        /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
        $response = $this->actingAs($user, 'professionals')->post(route('accept_order'), $payload);
        $response->assertStatus(200);
        $response->assertJson(['message' => 'Accepted']);
    }

    public function testProfessionalShouldRejectRequestOrder()
    {
        $this->refreshDatabase();
        $this->artisan('passport:install');

        $user = Professional::factory()->create();
        $request_order = RequestOrder::factory()->create();
        
        $payload = [
            'request_order_id' => $request_order->id,
        ];

        /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
        $response = $this->actingAs($user, 'professionals')->post(route('reject_order'), $payload);
        $response->assertStatus(200);
        $response->assertJson(['message' => 'Rejected']);
    }
}
