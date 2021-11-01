<?php

namespace App\Http\Controllers\Features;

use Exception;
use App\Exceptions\Status;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Exceptions\AddProfessionsDeniedException;
use App\Repositories\Features\ProfessionalProfessionsRepository;
use App\Traits\ApiToken;
use PHPUnit\Framework\InvalidDataProviderException;

class ProfessionalProfessionsController extends Controller
{
    use ApiResponser, ApiToken;

    protected $repository;

    public function __construct(ProfessionalProfessionsRepository $repository)
    {
        $this->repository = $repository;
    }
    
    public function addProfession(Request $request)
    {
        
        $validate = Validator::make($request->all(), [
            'profession_id' => 'required|uuid',
        ]);

        if ($validate->fails()) {
            return $this->error('Error on validating', 400);
        }

        $token = $this->findToken($request);
        $inputs = $request->only('profession_id');

        try {
            $this->repository->create($inputs, $token);
            return $this->success([], "Profession added with success");
        } catch (InvalidDataProviderException $e) {
            return $this->error($e->getMessage(), $e->getCode());
        } catch (AddProfessionsDeniedException $e) {
            return $this->error($e->getMessage(), $e->getCode());
        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

}
