<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\Status;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Validator;

class NewPasswordController extends Controller
{
    use ApiResponser;

    protected $repository;

    public function __construct()
    {

    }

    public function forgotPassword(Request $request)
    {

        $validate = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validate->fails()) {
            return $this->error('Error on validating', 400);
        }
        
        $status = Password::sendResetLink($request->only('email'));
        
        if ($status == Password::RESET_LINK_SENT) {
            return $this->success([], __($status));
        }
    }
    
    public function reset(Request $request)
    {
        
        $validate = Validator::make($request->all(), [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required',
        ]);
        
        if ($validate->fails()) {
            return $this->error('Error on validating', 400);
        }

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();
                $user->tokens()->delete();

                event(new PasswordReset($user));
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            return $this->success([], "Password reset successfully");
        }
    }
}
