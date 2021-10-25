<?php

namespace Tests\Feature\app\Http\Controllers\Service;

use Tests\TestCase;
use App\Models\Auth\User;

class ServiceControllerTest extends TestCase
{

    public function testCheckIfUserIsAuthenticate() {
        $response = $this->get(route('get_sections'));
        $response->assertStatus(302);
        $response->assertRedirect('/api/v1/home');
    }

    public function testCheckIfCanGetSections()
    {
        $code = 200;
        $user = User::factory()->create();

        /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
        $response = $this->actingAs($user, 'professionals')->get(route('get_sections'));
        $response->assertStatus($code);
        $response->assertJson([
            'status' => $code, 
            'success' => true, 
            'message' => ''
        ]);
        $response->assertJsonStructure([
            'data',
        ]);
    }

    public function testCheckIfCanGetProfessions()
    {
        $code = 200;
        $user = User::factory()->create();

        /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
        $response = $this->actingAs($user, 'users')->get(route('get_professions'));
        $response->assertStatus($code);
        $response->assertJson([
            'status' => $code, 
            'success' => true, 
            'message' => ''
        ]);
        $response->assertJsonStructure([
            'data',
        ]);
    }

    public function testCheckIfCanGetTasks()
    {
        $code = 200;
        $user = User::factory()->create();

        /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
        $response = $this->actingAs($user, 'users')->get(route('get_tasks'));
        $response->assertStatus($code);
        $response->assertJson([
            'status' => $code, 
            'success' => true, 
            'message' => ''
        ]);
        $response->assertJsonStructure([
            'data',
        ]);
    }
}
