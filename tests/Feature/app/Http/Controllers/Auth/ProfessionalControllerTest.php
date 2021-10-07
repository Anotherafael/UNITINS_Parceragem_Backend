<?php

namespace Tests\Feature\app\Http\Controllers\Auth;

use Tests\TestCase;
use App\Models\Service\Profession;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProfessionalControllerTest extends TestCase
{
    public function testProfessionalShouldNotBeCreatedWithInvalidInput()
    {
        $payload = [
            'name' => 'Rafael Freitas',
            'email' => 'Wrong email',
            'phone' => 12123412341,
            'document_id' => 12312312312,
            'password' => 'secret'
        ];

        $response = $this->post(route('professional_store'), $payload);
        $response->assertStatus(400);
        $response->assertJson(['errors' => ['main' => 'Invalid inputs']]);
    }

    public function testProfessionalShouldBeCreatedIfEverythingIsOkay()
    {
        $this->refreshDatabase();
        $this->artisan('passport:install');

        //$profession = Profession::where('name', '=', 'Psicólogo')->first();
        
        $payload = [
            'name' => 'Rafael Junior',
            'email' => 'rafael@emmail.com',
            'phone' => 12123423341,
            'document_id' => 12312323312,
            'password' => 'secret'
        ];

        $response = $this->post(route('professional_store'), $payload);
        $response->assertStatus(200);
        $response->assertJson(['errors' => ['main' => 'Created with success']]);
    }
}
