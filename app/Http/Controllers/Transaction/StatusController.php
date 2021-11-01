<?php

namespace App\Http\Controllers\Transaction;

use Exception;
use App\Exceptions\TransactionDeniedException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Transaction\StatusRepository;
use App\Traits\ApiResponser;
use App\Traits\ApiToken;

class StatusController extends Controller
{
    use ApiResponser, ApiToken;

    protected $repository;

    public function __construct(StatusRepository $repository)
    {
        $this->repository = $repository;
    }

    public function accept(Request $request)
    {   
        $validate = Validator::make($request->all(), [
            'request_order_id' => 'required',
        ]);

        if ($validate->fails()) {
            return $this->error("Error on validating", 400);
        }
        
        $token = $this->findToken($request);
        $inputs = $request->only('request_order_id');

        try {
            $this->repository->handleAccept($token, $inputs);
            return $this->success([], "Accepted");
        } catch (TransactionDeniedException $e) {
            return $this->error($e->getMessage(), $e->getCode());
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
    
    public function reject(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'request_order_id' => 'required',
        ]);
        
        if ($validate->fails()) {
            return $this->error("Error on validating", 400);
        }

        $token = $this->findToken($request);
        $inputs = $request->only('request_order_id');

        try {
            $this->repository->handleReject($token, $inputs);
            return $this->success([], "Rejected");
        } catch (TransactionDeniedException $e) {
            return $this->error($e->getMessage(), $e->getCode());
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

}