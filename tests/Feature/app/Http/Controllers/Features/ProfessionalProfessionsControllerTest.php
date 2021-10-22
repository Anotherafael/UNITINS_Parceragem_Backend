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

    public function testValidateInputs()
    {
        $code = 400;
        $this->refreshDatabase();
        $this->artisan('passport:install');
        
        $user = Professional::factory()->create();
        $payload = [
            'profession_id' => 'wrong id',
        ];

        $response = $this->post(route('add_professions', ['id' => $user->id]), $payload);
        $response->assertStatus($code);
        $response->assertJson([
            'status' => $code, 
            'success' => false, 
            'message' => 'Invalid inputs'
        ]);

    }

    public function testProfessionalShouldAddProfessions()
    {
        $code = 200;
        $this->refreshDatabase();
        $this->artisan('passport:install');

        $user = Professional::factory()->create();
        $profession = Profession::where('name', '=', 'Psícólogo')->first();

        $payload = [
            'profession_id' => $profession->id,
        ];

        /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
        $response = $this->actingAs($user, 'professionals')->post(route('add_professions'), $payload);
        $response->assertStatus($code);
        $response->assertJson([
            'status' => $code, 
            'success' => true, 
            'message' => 'Profession added with success'
        ]);
    }
}
