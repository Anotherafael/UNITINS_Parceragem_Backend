<?php

namespace App\Http\Controllers\Transaction;

use Exception;
use DateTimeZone;
use Carbon\Carbon;
use App\Traits\ApiToken;
use App\Exceptions\Status;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Traits\TokenConverter;
use App\Models\Transaction\Order;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Validator;
use App\Exceptions\TransactionDeniedException;
use App\Repositories\Transaction\OrderRepository;
use PHPUnit\Framework\InvalidDataProviderException;

class OrderController extends Controller
{

    use ApiResponser, ApiToken;

    protected $repository;

    public function __construct(OrderRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getMyOrders(Request $request)
    {
        $token = $this->findToken($request);
        
        try {
            $orders = $this->repository->getMyOrders($token);
            return $this->success($orders);
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
        
    }
    
    public function getPendingOrders() {

        $orders = Order::select('orders.*')
        ->where('status', '=', 1)
        ->get();
        
        return $this->success($orders);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validate = Validator::make($request->all(), [
            'task_id' => 'required|uuid',
            'price' => 'required|numeric',
            'date' => 'required|date',
            'hour' => 'required',
        ]);
        
        if ($validate->fails()) {
            return $this->error("Error on validating", 400);
        }
        
        $inputs = $request->all();
        $token = $this->findToken($request);

        try {
            $this->repository->create($inputs, $token);
            return $this->success([], "Created with success");
        } catch (TransactionDeniedException $e) {
            return $this->error($e->getMessage(), $e->getCode());
        } catch (InvalidDataProviderException $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $token = $this->findToken($request);
        
        try {
            $this->repository->cancel($token, $id);
            return $this->success([], "Order canceled");
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
