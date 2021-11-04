<?php


namespace App\Repositories\Auth;

use Exception;
use App\Models\Auth\User;
use Illuminate\Http\Request;
use App\Models\Auth\Professional;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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
        $model = $this->getProvider($token);
        $fields = $request->only('name', 'phone', 'photo_path');
        
        $user = $model->where('id', '=', $token->tokenable_id)->first();
        
        if($request->hasFile('photo_path')) {
            if($user->photo_path && Storage::exists($user->photo_path)) {
                Storage::delete($user->photo_path);
            }
            $imagePath = $fields['photo_path']->store('users');
            $fields['photo_path'] = $imagePath;
        }
        
        try {
            DB::beginTransaction();
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
