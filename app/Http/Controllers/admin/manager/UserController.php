<?php
/**
 * Created by PhpStorm.
 * User: lam
 * Date: 16-10-23
 * Time: 上午10:47
 */

namespace App\Http\Controllers\admin\manager;


use App\Role;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\M3Result;
use App\Http\Requests\CreateUserRequest;

class UserController extends Controller
{
    // 列出所有成员
    public function index(Request $request)
    {
        $users = User::all();

        $admins = array();
        $commonUsers = array();
        // 接受get参数
        $sort = $request->input('sort', 'asc');

        // 拆分身份
        // 区分超级管理员和普通成员
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
        
        return view('admin.manager.users.user_index')
            ->with('admins', $admins)
            ->with('commonUsers', $commonUsers)
            ->with('sort', $sort);
    }

    // 添加成员表单
    public function create()
    {
        return view('admin.manager.users.user_add');
    }

    // 添加成员
    public function store(CreateUserRequest $request)
    {
        // 写入成员表
        $user = User::create([
            'username' => $request->input('username'),
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
        ]);
        
        // 获取输入角色
        $role = $request->input('role', '');
        
        if($role == 'admin')
        {
            $admin = Role::where('name', '=', 'admin')->first();
            $user->attachRole($admin);
        }
        else if($role == 'commonUser')
        {
            $commonUser = Role::where('name', '=', 'common user')->first();
            $user->attachRole($commonUser);
        }
        
        // 注册成功后返回user_index页面
        return redirect('/manager/user');
    }
    
    // 删除成员
    public function destroy($user_id)
    {
        // 删除用户表中数据
        // 由于用Entrust提供的迁移命令生成的关联关系表中默认使用了onDelete('cascade')
        // 以便父级记录被删除后会移除其对应的关联关系。
        // 所以不用手动删除用户-角色映射表中数据
        User::destroy($user_id);

        // 删除用户拥有的角色（除去commonUser这个公共的）
        

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