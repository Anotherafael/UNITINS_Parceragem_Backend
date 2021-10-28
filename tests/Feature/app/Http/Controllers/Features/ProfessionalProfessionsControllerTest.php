<?php

namespace Tests\Feature\app\Http\Controllers\Features;

use Tests\TestCase;
use App\Models\Auth\User;
use App\Models\Auth\Professional;
use App\Models\Service\Profession;

class ProfessionalProfessionsControllerTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function user_access_should_be_denied_on_add_profession()
    {
        $this->refreshDatabase();

        $user = User::factory()->create();
        $profession = Profession::where('name', '=', 'Psícólogo')->first();

        $payload = [
            'profession_id' => $profession->id,
        ];

        /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
        $response = $this->actingAs($user, 'users')->post(route('add_professions'), $payload);
        $response->assertStatus(401);
        $response->assertJson([
            'status' => 'Error', 
            'message' => 'User is not authorized to make transactions'
        ]);
    }

    /** @test */
    public function professional_can_add_profession()
    {
        $this->refreshDatabase();

        $user = Professional::factory()->create();
        $profession = Profession::where('name', '=', 'Psícólogo')->first();

        $payload = [
            'profession_id' => $profession->id,
        ];

        /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
        $response = $this->actingAs($user, 'professionals')->post(route('add_professions'), $payload);
        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'Success', 
            'message' => 'Profession added with success'
        ]);
    }
}
