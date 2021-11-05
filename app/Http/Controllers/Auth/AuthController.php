<?php

namespace App\Http\Controllers\Auth;

use Exception;
use App\Models\Auth\User;
use App\Exceptions\Status;

use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Exceptions\SqlException;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Laravel\Sanctum\PersonalAccessToken;
use App\Repositories\Auth\AuthRepository;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\InvalidDataProviderException;

class AuthController extends Controller
{
    use ApiResponser;

    protected $repository;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct(AuthRepository $repository)
    {
        $this->repository = $repository;
    }

    public function register(Request $request, string $provider)
    {

        $validate = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email',
            'document_id' => 'string|required|min:14',
            'phone' => 'required|numeric',
            'password' => 'required|string|min:6',
            'photo_path' => 'image|mimes:jpg,jpeg,png',
        ]);

        if ($validate->fails()) {
            return $this->error('Error on validating', 400);
        }

        try {
            $user = $this->repository->register($request, $provider);
            return $this->success([
                'user' => $user
            ], "Registered with success");
        } catch (InvalidDataProviderException $e) {
            return $this->error($e->getMessage(), $e->getCode());
        } catch (SqlException $e) {
            return $this->error($e->getMessage(), $e->getCode());
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function login(Request $request, string $provider)
    {

        $validate = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        if ($validate->fails()) {
            return $this->error('Error on validating', 400);
        }

        $credentials = $request->only(['email', 'password']);

        try {
            $data = $this->repository->login($credentials, $provider);
            return $this->success($data, "Authenticated with success");
        } catch (AuthorizationException $e) {
            return $this->error($e->getMessage(), $e->getCode());
        } catch (InvalidDataProviderException $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }

    }

    public function logout(Request $request)
    {

        $requestToken = $request->header('authorization');
        $personalAccessToken = new PersonalAccessToken();
        $token = $personalAccessToken->findToken(str_replace('Bearer', '', $requestToken));
        $token->delete();

        return $this->success([], 'Token revoked');
    }
}
