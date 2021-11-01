<?php


namespace App\Repositories\Features;

use Exception;
use App\Traits\ApiResponser;
use App\Exceptions\SqlException;
use App\Models\Auth\Professional;
use App\Models\Service\Profession;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\AddProfessionsDeniedException;
use PHPUnit\Framework\InvalidDataProviderException;

class ProfessionalProfessionsRepository
{
    
    public function create(array $fields, $token)
    {
        if (!$this->modelCanAddProfession($token)) {
            throw new AddProfessionsDeniedException('User is not authorized to make transactions', 401);
        }

        try {
            DB::beginTransaction();

            $user = Professional::find($token->tokenable_id)->first();
            $profession = Profession::find($fields)->first();

            $user->professions()->attach($profession);

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            throw new Exception('Error on SQL transaction', 500);
        }
    }

    public function modelCanAddProfession($token) : bool {

        if ($token->tokenable_type == 'App\Models\Auth\User') {
            return false;
        } else if ($token->tokenable_type == 'App\Models\Auth\Professional') {
            return true;
        } else {
            throw new InvalidDataProviderException('Provider Not Found', 422);
        }

    }

}
