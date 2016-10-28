<?php

namespace App\Http\Controllers\Manager;

use App\Entity\Project;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function toList(Request $request)
    {
        $projects = Project::all();

        // 接受get参数
        $col = $request->input('col', '');
        $sort = $request->input('sort', '');

        switch ($col)
        {
            case "id":
                if ($sort == "desc")
                {
                    $customers = Customer::orderBy('id', 'DESC')->get();
                }
                else if($sort = "asc")
                {
                    $customers = Customer::orderBy('id', 'ASC')->get();
                }
                break;
        }
    }
}