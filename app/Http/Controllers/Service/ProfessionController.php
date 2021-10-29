<?php

namespace App\Http\Controllers\Service;

use Illuminate\Http\Request;
use App\Models\Service\Profession;
use App\Http\Controllers\Controller;
use App\Traits\ApiResponser;

class ProfessionController extends Controller
{

    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $professions = Profession::select("professions.*")->orderBy('professions.name')->get();
        return $this->success($professions);
    }

}
