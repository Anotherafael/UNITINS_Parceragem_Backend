<?php

namespace App\Http\Controllers\Transaction;

use Illuminate\Http\Request;
use App\Exceptions\OrderException;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Transaction\OrderRepository;
use PHPUnit\Framework\InvalidDataProviderException;

class OrderController extends Controller
{

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
            'service_id' => 'required',
            'price' => 'required',
            'date' => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json(['errors' => ['main' => 'Invalid inputs']], 400);
        }

        try {
            $this->repository->create($request->all());
            return response()->json(['message' => 'Created with success'], 200);
        } catch (OrderException $exception) {
            return response()->json(['message' => $exception->getMessage()], 401);
        } catch (InvalidDataProviderException $exception) {
            return response()->json(['message' => $exception->getMessage()], 422);
        } catch (\Exception $exception) {
            Log::critical('[Creating Order Gone So Wrong]', [
                'message' => $exception->getMessage()
            ]);
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
