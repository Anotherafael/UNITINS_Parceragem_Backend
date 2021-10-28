<?php

namespace Tests\Feature\app\Http\Controllers\Auth;

use Tests\TestCase;
use App\Models\Auth\User;

class NewPasswordControllerTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();
    }
    
    /** @test */
    public function user_can_receive_an_email_to_reset_password()
    {
        $this->migrate();
        $user = User::factory()->create();
        $user->name = "Serena";
        $user->save();
        
        $payload = [
            'email' => $user->email,
        ];
        
        $response = $this->post(route('forgot_password'), $payload);
        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'Success',
            'message' => 'We have emailed your password reset link!'
        ]);
    }

    /** @test */
    // public function user_can_add_new_password()
    // {
    //     $this->migrate();

    //     $user = User::where('name', '=', 'Serena')->first();

    //     $payload = [
    //         'token' => '8162b99b33e987319f190740327cab040bbf0fa3f7c735b9409f2e057bc871fb',
    //         'email' => $user->email,
    //         'password' => 'secret123',
    //     ];
        
    //     $response = $this->post(route('reset_password'), $payload);
    //     $response->assertStatus(200);
    //     $response->assertJson([
    //         'status' => 'Success',
    //         'message' => 'Password reset successfully'
    //     ]);
    // }

}
