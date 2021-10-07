<?php


namespace App\Repositories\Auth;

use Exception;
use Illuminate\Support\Str;
use App\Models\Auth\Professional;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ProfessionalRepository
{
    
    public function create(array $fields)
    {
        $fields['id'] = Str::uuid();
        $fields['password'] = Hash::make($fields['password']);
        
        try {
            DB::beginTransaction();
            Professional::create($fields);
            //$professional->profession()->attach($fields['profession_id']);
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            dd($e->getMessage());
            return response()->json(['errors' => ['main' => 'SQL Transaction Error']], 500);
        }
    }
}