<?php

namespace App\Http\Controllers\Auth;

use Exception;
use App\Exceptions\Status;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Auth\UserRepository;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\InvalidDataProviderException;

class UserController extends Controller
{

    protected $repository;

    public function __construct(UserRepository $repository)
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
    public function store(Request $request, string $provider)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|numeric',
            'password' => 'required',
            'document_id' => 'required'
        ]);

        if ($validate->fails()) {
            return $this->sendError(Status::getStatusMessage(400), [], 400);
        }
        
        try {
            $this->repository->create($request->all(), $provider);
            return $this->sendResponse([], "Created with success");
        } catch (InvalidDataProviderException $e) {
            return $this->sendError($e->getMessage(), [], 422);
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

}
