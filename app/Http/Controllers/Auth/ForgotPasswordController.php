<?php

namespace App\Http\Controllers\Auth;

use App\Traits\ApiToken;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class ForgotPasswordController extends Controller
{
    use ApiResponser, ApiToken;

    public function forgotPassword(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validate->fails()) {
            return $this->error('Error on validating', 400);
        }

        $status = Password::sendResetLink($request->only('email'));
        dd($status);

        if ($status == Password::RESET_LINK_SENT) {
            return $this->success([], __($status));
        }
    }
}
