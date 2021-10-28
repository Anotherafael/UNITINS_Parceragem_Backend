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

    /** @test */
    public function user_access_should_be_denied_at_creating_an_order()
    {
        $this->refreshDatabase();

        $service = Task::where('name', '=', 'Ansiedade')->first();
        $user = User::factory()->create();

        $payload = [
            'task_id' => $service->id,
            'price' => 1000.0,
            'date' => Carbon::now()
        ];

        /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
        $response = $this->actingAs($user, 'users')->post(route('add_order'), $payload);
        $response->assertStatus(401);
        $response->assertJson([
            'status' => 'Error', 
            'message' => 'User is not allowed to create orders'
        ]);
    }

    /** @test */
    public function professional_is_allowed_to_create_orders()
    {
        $this->refreshDatabase();

        $service = Task::where('name', '=', 'Depressão')->first();
        $user = Professional::factory()->create();
        
        $payload = [
            'task_id' => $service->id,
            'price' => 1000.0,
            'date' => Carbon::now()
        ];
        
        /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
        $response = $this->actingAs($user, 'professionals')->post(route('add_order'), $payload);
        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'Success',
            'message' => 'Created with success'
        ]);
    }
}
