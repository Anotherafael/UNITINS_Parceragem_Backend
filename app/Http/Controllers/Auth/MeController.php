<?php

namespace App\Http\Controllers\Auth;

use App\Traits\ApiToken;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Auth\MeRepository;
use Exception;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\InvalidDataProviderException;

class MeController extends Controller
{

    use ApiToken, ApiResponser;
    
    protected $repository;

    public function __construct(MeRepository $repository)
    {
        $this->repository = $repository;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $token = $this->findToken($request);

        try {
            $me = $this->repository->getMe($token);
            return $this->success($me);
        } catch (InvalidDataProviderException $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'string',
            'phone' => 'string',
            'new_password' => 'string|min:6',
        ]);

        if ($validate->fails()) {
            return $this->error("Error on validating", 400);
        }
        
        $token = $this->findToken($request);

        try {
            $this->repository->updateMe($request, $token);
            return $this->success([], "Updated with success");
        } catch (InvalidDataProviderException $e) {
            return $this->error($e->getMessage(), $e->getCode());
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

}
