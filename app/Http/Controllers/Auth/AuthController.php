<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\Status;
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
            return $this->sendError(Status::getStatusMessage(400), [], 400);
        }

        try {
            $fields = $request->only(['email', 'password']);
            $result = $this->repository->authenticate($provider, $fields);
            return $this->sendResponse($result, "Authenticated with success");
        } catch (InvalidDataProviderException $exception) {
            return $this->sendError($exception->getMessage(), [], 422);
        } catch (AuthorizationException $exception) {
            return $this->sendError($exception->getMessage(), [], 401);
        }

    }
}