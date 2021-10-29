<?php

namespace App\Http\Controllers\Service;

use App\Models\Service\Task;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\ApiResponser;

class TaskController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tasks = Task::select("tasks.*")->orderBy('tasks.name')->get();
        return $this->success($tasks);
    }

}
