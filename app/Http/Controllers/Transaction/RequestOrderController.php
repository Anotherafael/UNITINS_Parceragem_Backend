<?php

namespace App\Http\Controllers\Transaction;

use Exception;
use App\Exceptions\Status;
use App\Exceptions\TransactionDeniedException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Transaction\RequestOrderRepository;
use App\Traits\ApiResponser;
use App\Traits\ApiToken;

class RequestOrderController extends Controller
{

    use ApiResponser, ApiToken;

    protected $repository;

    public function __construct(RequestOrderRepository $repository)
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
        //
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
            'order_id' => 'required|uuid',
        ]);

        if ($validate->fails()) {
            return $this->error("Error on validating", 400);
        }
        
        $inputs = $request->all();
        $token = $this->findToken($request);

        try {
            $this->repository->create($inputs, $token);
            return $this->success([], "Request sent");
        } catch (TransactionDeniedException $e) {
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
