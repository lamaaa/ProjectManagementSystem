<?php
/**
 * Created by PhpStorm.
 * User: lam
 * Date: 16-10-23
 * Time: 上午10:47
 */

namespace App\Http\Controllers\admin;


use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    // 列出所有用户
    public function index(Request $request)
    {
        $users = User::all();

        $admins = array();
        $commonUsers = array();
        // 接受get参数
        $sort = $request->input('sort', 'asc');

        // 拆分身份
        // 区分超级管理员和普通用户
        foreach ($users as $user)
        {
            if ($user->hasRole('admin'))
            {
                $admins[] = $user;
            }
            else
            {
                $commonUsers[] = $user;
            }
        }

        return view('admin.users.user_index')
            ->with('admins', $admins)
            ->with('commonUsers', $commonUsers)
            ->with('sort', $sort);
    }

    // 添加用户表单
    public function create()
    {
        return view('admin.users.user_add');
    }

    // 添加用户
    public function store(Request $request)
    {
        // 写入用户表
        User::create([
            'username' => $request->input('username'),
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
        ]);

        $m3_result = new M3Result();
        $m3_result->status = 0;
        $m3_result->message = '添加成功';

        return $m3_result->toJson();
    }

    public function toUpdatePassword(Request $request)
    {
        $id = $request->input('id', '');
        $account = User::findOrFail($id);
        return view('manager.account_edit')
            ->with('account', $account);
    }

    public function updatePassword(Request $request)
    {
        $id = $request->input('id', '');
        $password = $request->input('password', '');

        $account = User::findOrFail($id);
        $account->password = $password;
        $account->save();

        $m3_result = new M3Result();
        $m3_result->status = 0;
        $m3_result->message = '重置成功';

        return $m3_result->toJson();
    }

    public function delete(Request $request)
    {
        $id = $request->input('id', '');
        DB::table('users')->where('id', '=', $id)->delete();

        return redirect('manager/account_manage');
    }
}