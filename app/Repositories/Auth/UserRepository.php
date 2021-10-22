<?php


namespace App\Repositories\Auth;

use Exception;
use App\Models\Auth\User;
use Illuminate\Support\Str;
use App\Models\Auth\Professional;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\InvalidDataProviderException;

class UserRepository
{

    public function create(array $fields, $provider)
    {

        $selectedProvider = $this->getProvider($provider);

        $fields['id'] = Str::uuid();
        $fields['password'] = Hash::make($fields['password']);
        
        try {
            DB::beginTransaction();
            $selectedProvider->create($fields);
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            dd($e->getMessage());
            return response()->json(['errors' => ['main' => 'SQL Transaction Error']], 500);
        }
    }

    public function getProvider(string $provider)
    {
        if ($provider == "users") {
            return new User();
        } else if ($provider == "professionals") {
            return new Professional();
        } else {
            throw new InvalidDataProviderException('Provider Not Found');
        }
    }
}
