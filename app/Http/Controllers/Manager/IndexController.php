<?php
/**
 * Created by PhpStorm.
 * User: lam
 * Date: 16-10-22
 * Time: 下午7:01
 */

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function toIndex(Request $request)
    {
        $name = $request->session()->get('user')->name;
        return view('manager.index')
            ->with('name', $name);
    }

    public function toWelcome()
    {
        return view('welcome');
    }
}