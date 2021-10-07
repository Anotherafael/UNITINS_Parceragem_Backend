<?php


namespace App\Repositories\Auth;

use Exception;
use App\Models\Auth\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserRepository
{

    public function create(array $fields)
    {
        $fields['id'] = Str::uuid();
        $fields['password'] = Hash::make($fields['password']);
        
        try {
            DB::beginTransaction();
            User::create($fields);
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            dd($e->getMessage());
            return response()->json(['errors' => ['main' => 'SQL Transaction Error']], 500);
        }
    }
}
