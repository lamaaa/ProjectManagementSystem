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
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserPasswordRequest;

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
            $adminRole = Role::where('name', '=', 'admin')->first();
            $user->attachRole($adminRole);
        }
        else if($role == 'commonUser')
        {
            $commonUserRole = Role::where('name', '=', 'commonUser')->first();
            $user->attachRole($commonUserRole);
        }

        $result['status'] = 0;
        $result['message'] = '添加成功';

        return $result;
    }
    
    // 删除成员
    public function destroy($id)
    {
        // 通过成员id找到该成员
        $delete_user = User::findOrFail($id);

        // 删除该成员拥有的所有角色（除了“admin”和“commonUser”）
        foreach ($delete_user->roles as $role)
        {
            if ( $role->name != 'admin' && $role->name != 'commonUser')
            {
                $role->delete();
            }
        }

        // 删除用户表中数据
        // 由于用Entrust提供的迁移命令生成的关联关系表中默认使用了onDelete('cascade')
        // 以便父级记录被删除后会移除其对应的关联关系。
        // 所以不用手动删除用户-角色映射表中数据
        User::destroy($id);

        return redirect('/manager/user');
    }

    // 修改密码表单
    public function edit($id)
    {
        $user = User::findOrFail($id);

        return view('admin.manager.users.user_edit')
            ->with('user', $user);
    }

    // 重置密码
    public function update(UpdateUserPasswordRequest $request)
    {
        $id = $request->input('id');
        $password = $request->input('password');

        $user = User::findOrFail($id);
        $user->password = bcrypt($password);
        $user->save();

        $result['status'] = 0;
        $result['message'] = '添加成功';

        return $result;
    }
}