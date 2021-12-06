<?php

namespace App\Http\Controllers\Service;

use Illuminate\Http\Request;
use App\Models\Auth\Professional;
use App\Models\Service\Profession;
use App\Http\Controllers\Controller;
use App\Traits\ApiResponser;
use App\Traits\ApiToken;

class ProfessionController extends Controller
{

    use ApiResponser, ApiToken;

    public function index(Request $request)
    {
        $professions = Profession::select("professions.*")
        ->with('section')
        ->where('professions.section_id', '=', $request->id)
        ->orderBy('professions.name')
        ->get();
        return $this->success($professions);
    }

    public function allProfessions(Request $request)
    {

        $token = $this->findToken($request);

        $user = Professional::find($token->tokenable_id);
        $professionsUser = $user->professions;
        $professions = Profession::select("professions.*")
        ->with('section')
        ->orderBy('professions.name')
        ->get();
        $differentItems = $professions->diff($professionsUser);

        return $this->success($differentItems);
    }
    
}
