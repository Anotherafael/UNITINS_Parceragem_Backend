<?php

namespace App\Http\Controllers\Service;

use Illuminate\Http\Request;
use App\Models\Service\Profession;
use App\Http\Controllers\Controller;
use App\Traits\ApiResponser;

class ProfessionController extends Controller
{

    use ApiResponser;

    public function index(Request $request)
    {
        $professions = Profession::select("professions.*")
        ->with('section')
        ->where('professions.section_id', '=', $request->id)
        ->orderBy('professions.name')
        ->get();
        return $this->success($professions);
    }

}
