<?php

namespace Tests\Feature\app\Http\Controllers\Transaction;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Auth\User;
use Laravel\Passport\Passport;
use App\Models\Service\Service;
use App\Models\Auth\Professional;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderControllerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function testUserShouldNotCreateAnOrder()
    {

        $this->refreshDatabase();
        $this->artisan('passport:install');

        $service = Service::where('name', '=', 'Ansiedade')->first();
        $user = User::factory()->create();

        $payload = [
            'service_id' => $service->id,
            'price' => 1000.0,
            'date' => Carbon::now()
        ];

        /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
        $response = $this->actingAs($user, 'users')->post(route('add_order'), $payload);
        $response->assertStatus(401);
        $response->assertJson(['message' => 'Function not authorized']);
    }

    public function testProfessionalShouldCreateAnOrder()
    {

        $this->refreshDatabase();
        $this->artisan('passport:install');

        $service = Service::where('name', '=', 'Ansiedade')->first();
        $user = Professional::factory()->create();
        
        $payload = [
            'service_id' => $service->id,
            'price' => 1000.0,
            'date' => Carbon::now()
        ];
        
        /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
        $response = $this->actingAs($user, 'users')->post(route('add_order'), $payload);
        $response->assertStatus(200);
        $response->assertJson(['message' => 'Created with success']);
    }
}
