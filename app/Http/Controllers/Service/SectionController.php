<?php

namespace App\Http\Controllers\Service;

use App\Traits\ApiResponser;
use App\Models\Service\Section;
use App\Http\Controllers\Controller;

class SectionController extends Controller
{
    use ApiResponser;

    public function __construct()
    {
    }
    
    public function index()
    {
        $data = Section::select("sections.*")->orderBy('sections.name')->get();
        return $this->success($data);
    }

}
