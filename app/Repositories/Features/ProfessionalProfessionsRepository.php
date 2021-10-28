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
    use ApiResponser;
    
    public function create(array $fields)
    {
        dd($fields);
        if (!$this->guardCanAddProfession()) {
            throw new AddProfessionsDeniedException('User is not authorized to make transactions', 401);
        }

        try {
            DB::beginTransaction();

            $user = Professional::find(Auth::user()->id)->first();
            $profession = Profession::find($fields['profession_id'])->first();

            $user->professions()->attach($profession);

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            throw new Exception('Error on SQL transaction', 500);
        }
    }

    public function guardCanAddProfession() : bool {

        dd(Auth::guard('users')->check());
        if (Auth::guard('users')->check()) {
            return false;
        } else if (Auth::guard('professionals')->check()) {
            return true;
        } else {
            throw new InvalidDataProviderException('Provider Not Found', 422);
        }

    }

}
