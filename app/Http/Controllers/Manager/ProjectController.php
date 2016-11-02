<?php

namespace App\Http\Controllers\Manager;

use App\Entity\Project;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function toList(Request $request)
    {
        // 接受get参数
        $col = $request->input('col', 'asc');
        $sort = $request->input('sort', 'id');

        $projects = $this->sortWith($sort, $col);

        return view('manager.project_list')
            ->with('projects', $projects)
            ->with('sort', $sort);
    }

    public function sortWith($sort, $col)
    {
        if($sort == "desc")
        {
            return Project::orderBy($col, 'desc')->get();
        }
        else if($sort == "asc")
        {
            return Project::orderBy($col, 'asc')->get();
        }
    }
}