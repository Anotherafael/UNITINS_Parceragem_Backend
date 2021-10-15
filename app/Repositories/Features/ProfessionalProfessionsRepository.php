<?php


namespace App\Repositories\Features;

use Exception;
use App\Models\Auth\Professional;
use App\Models\Service\Profession;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ProfessionalProfessionsRepository
{

    public function create(array $fields, $id)
    {

        try {
            DB::beginTransaction();

            $user = Professional::where('id', '=', $id)->first();
            $profession = Profession::where('id', '=', $fields['profession'])->first();

            $user->professions()->attach($profession);

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            dd($e->getMessage());
            return response()->json(['errors' => ['main' => 'SQL Transaction Error']], 500);
        }
    }

}
