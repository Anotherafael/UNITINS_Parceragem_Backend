<?php


namespace App\Repositories\Auth;

use Exception;
use App\Models\Auth\User;
use App\Mail\ResetPasswordEmail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class NewPasswordRepository
{

    public function sendEmail(array $fields)
    {

        $user = User::where('email', '=', $fields['email'])->first();

        Mail::to($user->email)->send(new ResetPasswordEmail($user));
    }

    public function addNewPassword(array $fields, $email)
    {

        try {
            DB::beginTransaction();
            $user = User::where('email', '=', $email)->first();
            $user->password = Hash::make($fields['password']);
            $user->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            dd($e->getMessage());
            return response()->json(['errors' => ['main' => 'SQL Transaction Error']], 500);
        }
    }
}
