<?php

namespace App\Http\Controllers;

use App\Models\M3Result;
use Illuminate\Http\Request;

use App\Entity\User;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    public function toLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $username = $request->input('username', '');
        $password = $request->input('password', '');

        $m3_result = new M3Result();

        if($username == '' || $password == '')
        {
            $m3_result->status = 1;
            $m3_result->message = "帐号或密码不能为空";
            return $m3_result->toJason();
        }

        $user = User::where('username', $username)->where('password', $password)->first();

        if(!$user)
        {
            $m3_result->status = 2;
            $m3_result->message = "帐号或密码错误";
        }
        else
        {
            $m3_result->status = 0;
            $m3_result->message = $user->role;

            $request->session()->put('user', $user);
        }

        return $m3_result->toJason();
    }

    public function toExit(Request $request)
    {
        $request->session()->forget('user');
        return view('auth.login');
    }
}
