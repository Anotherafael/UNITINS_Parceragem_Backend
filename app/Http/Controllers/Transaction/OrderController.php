<?php

namespace App\Http\Controllers\Transaction;

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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::select("orders.*")->get();
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
            'task_id' => 'required',
            'price' => 'required',
            'date' => 'required',
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
