<?php


namespace App\Repositories\Auth;

use Exception;
use App\Models\Auth\User;
use Illuminate\Support\Str;
use App\Traits\ApiResponser;
use App\Exceptions\SqlException;

use App\Models\Auth\Professional;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Access\AuthorizationException;
use PHPUnit\Framework\InvalidDataProviderException;

class AuthRepository
{

    use ApiResponser;

    public function register(array $fields, string $provider)
    {
        $selectedProvider = $this->getProvider($provider);

        if ($this->isExistingUser($selectedProvider, $fields)) {
            throw new SqlException('User already exist', 500);
        }

        try {
            DB::beginTransaction();
            $fields['id'] = Str::uuid();
            $fields['password'] = Hash::make($fields['password']);
            $user = $selectedProvider->create($fields);
            DB::commit();
            return $user;
        } catch (Exception $e) {
            DB::rollback();
            throw new Exception('Error on SQL Transaction', 500);
        }
    }

    public function login(array $fields, string $provider): array
    {

        $selectedProvider = $this->getProvider($provider);
        $model = $selectedProvider->where('email', $fields['email'])->first();

        if (!$model) {
            throw new AuthorizationException('Wrong credentials', 401);
        }

        if (!Hash::check($fields['password'], $model->password)) {
            throw new AuthorizationException('Wrong credentials', 401);
        }
        
        $token = $model->createToken($model->email);

        return [
            'access_token' => $token->plainTextToken,
            'user' => $model
        ];
    }

    public function getProvider(string $provider)
    {
        if ($provider == "users") {
            return new User();
        } else if ($provider == "professionals") {
            return new Professional();
        } else {
            throw new InvalidDataProviderException('Provider Not Found', 422);
        }
    }

    public function isExistingUser($provider, $fields)
    {

        $model = $provider->where('email', $fields['email'])->first();
        if ($model) return true;
        $model = $provider->where('document_id', $fields['document_id'])->first();
        if ($model) return true;

        return false;
    }
}
