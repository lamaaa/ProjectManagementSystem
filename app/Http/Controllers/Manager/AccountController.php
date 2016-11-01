<?php
/**
 * Created by PhpStorm.
 * User: lam
 * Date: 16-10-23
 * Time: 上午10:47
 */

namespace App\Http\Controllers\Manager;


use App\Entity\User;
use App\Http\Controllers\Controller;
use App\Models\M3Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;

class AccountController extends Controller
{
    public function toList(Request $request)
    {
        $accounts = User::all();

        // 接受get参数
        $sort = $request->input('sort', '');

        // 拆分身份
        $designers = User::where('role', '=', '设计师')->get();
        $admins = User::where('role', '=', '管理员')->get();
        $pms = User::where('role', '=', '项目经理')->get();
        $engineers = User::where('role', '=', '工程师')->get();

        return view('manager.account_list')
            ->with('accounts', $accounts)
            ->with('sort', $sort)
            ->with('designers', $designers)
            ->with('engineers', $engineers)
            ->with('pms', $pms)
            ->with('admins', $admins);
    }

    public function toAdd()
    {
        return view('manager.account_add');
    }

    public function add(Request $request)
    {
        User::create($request->all());

        $m3_result = new M3Result();
        $m3_result->status = 0;
        $m3_result->message = '添加成功';

        return $m3_result->toJason();
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

        return $m3_result->toJason();
    }

    public function delete(Request $request)
    {
        $id = $request->input('id', '');
        DB::table('users')->where('id', '=', $id)->delete();

        return redirect('manager/account_manage');
    }
}