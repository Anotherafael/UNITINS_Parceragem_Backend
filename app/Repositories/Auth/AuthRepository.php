<?php


namespace App\Repositories\Auth;

use Exception;
use App\Models\Auth\User;
use Illuminate\Support\Str;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

use App\Exceptions\SqlException;
use App\Models\Auth\Professional;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Access\AuthorizationException;
use PHPUnit\Framework\InvalidDataProviderException;

class AuthRepository
{

    use ApiResponser;

    public function register(Request $request, string $provider)
    {
        $selectedProvider = $this->getProvider($provider);
        $fields = $request->all();
        
        if ($this->isExistingUser($selectedProvider, $fields)) {
            throw new SqlException('User already exist', 500);
        }
        
        try {
            DB::beginTransaction();

            if($request->hasFile('photo_path')) {
                $ext = $request->file('photo_path')->getClientOriginalExtension();
                $fileName = Str::random(10).".".$ext;

                $imagePath = $request->photo_path->storeAs('users/images', $fileName);
                $fields['photo_path'] = $imagePath;
            }

            $fields['id'] = Str::uuid();
            $fields['password'] = Hash::make($fields['password']);
            
            $user = $selectedProvider->create($fields);

            DB::commit();
            return $user;
        } catch (Exception $e) {
            DB::rollback();
            dd($e->getMessage());
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
