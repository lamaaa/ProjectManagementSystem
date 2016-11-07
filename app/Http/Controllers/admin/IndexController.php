<?php
/**
 * Created by PhpStorm.
 * User: lam
 * Date: 16-10-22
 * Time: 下午7:01
 */

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    public function toIndex(Request $request)
    {
        $name = Auth::user()->name;
        return view('admin.index')
            ->with('name', $name);
    }

    public function toWelcome()
    {
        return view('welcome');
    }
}