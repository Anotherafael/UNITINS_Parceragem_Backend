<?php

namespace App\Http\Controllers\Transaction;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Transaction\StatusRepository;

class StatusController extends Controller
{
    protected $repository;

    public function __construct(StatusRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function accept(Request $request)
    {   
        $validate = Validator::make($request->all(), [
            'request_order_id' => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json(['message' => 'Invalid inputs'], 400);
        }

        try {
            $this->repository->handleAccept($request->all());
            return response()->json(['message' => 'Accepted'], 200);
        } catch (Exception $e) {
            $e->getMessage();
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function reject(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'request_order_id' => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json(['message' => 'Invalid inputs'], 400);
        }

        try {
            $this->repository->handleReject($request->all());
            return response()->json(['message' => 'Rejected'], 200);
        } catch (Exception $e) {
            $e->getMessage();
        }
    }

}