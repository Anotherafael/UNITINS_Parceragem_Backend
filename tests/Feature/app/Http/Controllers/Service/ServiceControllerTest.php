<?php

namespace Tests\Feature\app\Http\Controllers\Service;

use Tests\TestCase;
use App\Models\Auth\User;

class ServiceControllerTest extends TestCase
{

    /** @test */
    public function check_if_can_get_sections()
    {
        $this->migrate();
        $user = User::factory()->create();

        /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
        $response = $this->actingAs($user, 'users')->get(route('get_sections'));
        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'Success',
            'message' => ''
        ]);
        $response->assertJsonStructure([
            'data',
        ]);
    }

    /** @test */
    public function check_if_can_get_professions()
    {
        $this->migrate();
        $user = User::factory()->create();

        /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
        $response = $this->actingAs($user, 'users')->get(route('get_professions'));
        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'Success',
            'message' => ''
        ]);
        $response->assertJsonStructure([
            'data',
        ]);
    }

    /** @test */
    public function check_if_can_get_tasks()
    {
        $this->migrate();
        $user = User::factory()->create();

        /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
        $response = $this->actingAs($user, 'users')->get(route('get_tasks'));
        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'Success',
            'message' => ''
        ]);
        $response->assertJsonStructure([
            'data',
        ]);
    }
}
