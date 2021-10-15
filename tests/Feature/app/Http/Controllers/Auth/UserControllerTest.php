<?php

namespace Tests\Feature\app\Http\Controllers\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserControllerTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();
    }

    public function testUserShouldNotBeCreatedWithInvalidInputOrWrongProvider()
    {
        $payload = [
            'name' => 'Rafael Freitas',
            'email' => 'Wrong email',
            'phone' => 12123412341,
            'document_id' => 12312312312,
            'password' => 'secret'
        ];

        $response = $this->post(route('user_register', ['provider' => 'professionassls']), $payload);
        $response->assertStatus(400);
        $response->assertJson(['errors' => ['main' => 'Invalid inputs']]);
    }

    public function testUserShouldBeCreatedIfEverythingIsOkay()
    {
        $this->refreshDatabase();
        $this->artisan('passport:install');

        $payload = [
            'name' => 'Rafael Freitas',
            'email' => 'rafael@email.com',
            'phone' => 12123412341,
            'document_id' => 12312312312,
            'password' => 'secret'
        ];

        $response = $this->post(route('user_register', ['provider' => 'users']), $payload);
        $response->assertStatus(200);
        $response->assertJson(['errors' => ['main' => 'Created with success']]);
    }

}
