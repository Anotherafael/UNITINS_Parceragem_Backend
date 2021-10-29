<?php

namespace App\Http\Controllers\Service;

use App\Traits\ApiResponser;
use App\Models\Service\Section;
use App\Http\Controllers\Controller;

class SectionController extends Controller
{
    use ApiResponser;
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $professions = Section::select("sections.*")->orderBy('sections.name')->get();
        return $this->success($professions);
    }

}
