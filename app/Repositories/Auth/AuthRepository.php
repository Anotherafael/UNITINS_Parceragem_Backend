<?php


namespace App\Repositories\Auth;


use App\Models\Auth\Professional;
use App\Models\Auth\User;
use Illuminate\Auth\Access\AuthorizationException;

use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\InvalidDataProviderException;

class AuthRepository
{
    public function authenticate(string $provider, array $fields): array
    {

        $selectedProvider = $this->getProvider($provider);
        $model = $selectedProvider->where('email', $fields['email'])->first();

        if (!$model) {
            throw new AuthorizationException('Wrong credentials', 401);
        }

        if (!Hash::check($fields['password'], $model->password)) {
            throw new AuthorizationException('Wrong credentials', 401);
        }

        $token = $model->createToken($provider);

        return [
            'access_token' => $token->plainTextToken,
            'provider' => $provider
        ];
    }

    public function getProvider(string $provider)
    {
        if ($provider == "users") {
            return new User();
        } else if ($provider == "professionals") {
            return new Professional();
        } else {
            throw new InvalidDataProviderException('Provider Not found');
        }
    }
}