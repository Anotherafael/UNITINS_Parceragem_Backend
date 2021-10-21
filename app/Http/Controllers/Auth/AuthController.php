<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Repositories\Auth\AuthRepository;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Access\AuthorizationException;
use PHPUnit\Framework\InvalidDataProviderException;

class AuthController extends Controller
{

    protected $repository;

    public function __construct(AuthRepository $repository)
    {
        $this->repository = $repository;
    }

    public function postAuthenticate(Request $request, string $provider)
    {

        $validate = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validate->fails()) {
            return response()->json(['errors' => ['main' => 'Invalid inputs']], 400);
        }

        try {
            $fields = $request->only(['email', 'password']);
            $result = $this->repository->authenticate($provider, $fields);
            return response()->json($result);
        } catch (InvalidDataProviderException $exception) {
            return response()->json(['errors' => ['main' => $exception->getMessage()]], 422);
        } catch (AuthorizationException $exception) {
            return response()->json(['errors' => ['main' => $exception->getMessage()]], 401);
        } catch (\Exception $exception) {
            return response()->json(['errors' => ['main' => $exception->getMessage()]], 123);
        }

    }
}