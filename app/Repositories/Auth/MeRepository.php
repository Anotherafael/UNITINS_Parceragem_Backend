<?php


namespace App\Repositories\Auth;

use Exception;
use App\Models\Auth\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Auth\Professional;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Auth\Access\AuthorizationException;
use PHPUnit\Framework\InvalidDataProviderException;

class MeRepository
{

    public function getMe($token)
    {
        $model = $this->getProvider($token);

        $user = $model->where('id', '=', $token->tokenable_id)->first();

        return $user;
    }

    public function updateMe(Request $request, $token)
    {
        $fields = $request->only('name', 'phone', 'photo_path', 'password', 'new_password');
        $selectedProvider = $this->getProvider($token);
        $user = $selectedProvider->where('id', '=', $token->tokenable_id)->first();

        if(!Hash::check($fields['password'], $user->password)) {
            throw new AuthorizationException('Wrong credentials', 401);
        }

        if ($request->hasFile('photo_path')) {
            if ($user->photo_path && Storage::exists($user->photo_path)) {
                Storage::delete($user->photo_path);
            }
            $ext = $request->file('photo_path')->getClientOriginalExtension();
            $fileName = Str::random(10) . "." . $ext;

            $imagePath = $request->photo->storeAs('users/images', $fileName);
            $fields['photo_path'] = $imagePath;
        }

        try {
            DB::beginTransaction();
            $fields['password'] = Hash::make($fields['new_password']);
            $user->fill($fields);
            $user->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            throw new Exception('Error on SQL Transaction', 500);
        }
    }

    public function getProvider($token)
    {
        if ($token->tokenable_type == 'App\Models\Auth\User') {
            return new User();
        } else if ($token->tokenable_type == 'App\Models\Auth\Professional') {
            return new Professional();
        } else {
            throw new InvalidDataProviderException('Provider Not Found', 422);
        }
    }
}
