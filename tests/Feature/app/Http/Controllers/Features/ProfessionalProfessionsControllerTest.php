<?php

namespace Tests\Feature\app\Http\Controllers\Features;

use Tests\TestCase;
use App\Models\Auth\Professional;
use App\Models\Service\Profession;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProfessionalProfessionsControllerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function testUserShouldNotSendInvalidInput()
    {
        $this->refreshDatabase();
        $this->artisan('passport:install');

        $user = Professional::factory()->create();
        $profession = Profession::where('name', '=', 'Psícólogo')->first();

        $payload = [
            
        ];

        $response = $this->post(route('add_professions', ['id' => $user->id]), $payload);
        $response->assertStatus(400);
        $response->assertJson(['errors' => ['main' => 'Invalid inputs']]);
    }

    public function testIfWorks()
    {
        $this->refreshDatabase();
        $this->artisan('passport:install');

        $user = Professional::factory()->create();
        $profession = Profession::where('name', '=', 'Psícólogo')->first();

        $payload = [
            'profession' => $profession->id,
        ];

        $response = $this->post(route('add_professions', ['id' => $user->id]), $payload);
        $response->assertStatus(200);
        $response->assertJson(['message' => 'everything okay, lil bro']);
    }
}
