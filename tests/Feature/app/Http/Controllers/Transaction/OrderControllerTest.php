<?php

namespace Tests\Feature\app\Http\Controllers\Transaction;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Auth\User;
use App\Models\Service\Service;
use App\Models\Auth\Professional;
use App\Models\Service\Task;

class OrderControllerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function testUserShouldNotCreateAnOrder()
    {
        $code = 401;
        $this->refreshDatabase();
        $this->artisan('passport:install');

        $service = Task::where('name', '=', 'Ansiedade')->first();
        $user = User::factory()->create();

        $payload = [
            'service_id' => $service->id,
            'price' => 1000.0,
            'date' => Carbon::now()
        ];

        /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
        $response = $this->actingAs($user, 'users')->post(route('add_order'), $payload);
        $response->assertStatus($code);
        $response->assertJson([
            'status' => $code, 
            'success' => false, 
            'message' => 'Unauthorized'
        ]);
    }

    public function testProfessionalShouldCreateAnOrder()
    {
        $code = 200;
        $this->refreshDatabase();
        $this->artisan('passport:install');

        $service = Task::where('name', '=', 'Ansiedade')->first();
        $user = Professional::factory()->create();
        
        $payload = [
            'service_id' => $service->id,
            'price' => 1000.0,
            'date' => Carbon::now()
        ];
        
        /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
        $response = $this->actingAs($user, 'professionals')->post(route('add_order'), $payload);
        $response->assertStatus($code);
        $response->assertJson([
            'status' => $code, 
            'success' => true, 
            'message' => 'Created with success'
        ]);
    }
}
