<?php
/**
 * Created by PhpStorm.
 * User: lam
 * Date: 16-10-23
 * Time: 上午10:43
 */

namespace App\Http\Controllers\Manager;


use App\Entity\Customer;
use App\Entity\Project_source;
use App\Entity\User;
use App\Http\Controllers\Controller;
use App\Models\M3Result;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public static $customers = array();
    public static $customers_hold = array();

    public function toList(Request $request)
    {
        // 获取所有客户
        $customers = Customer::all();
        $pms = User::where('role', '=', '项目经理')->get();
        // 获取作为排序的列和顺序
        $col = $request->input('col', '');
        $sort = $request->input('sort', '');

        $filter = 'all';
        if($col !== "")
        {
            $customers = $this->sortWith($filter, $sort, $col);
        }

        $filter_name = $request->input('filter_name', '');
        $value = $request->input('value', '');
        $export = $request->input('export', '');

        if($filter_name != null)
        {
            $customers = Customer::where($filter_name, '=', $value)->get();
        }

        return view('manager.customer_list')
            ->with('customers', $customers)
            ->with('sort', $sort)
            ->with('filter_name', $filter_name)
            ->with('query_value', $request->get('value'))
            ->with('pms', $pms);
    }

    public function sortWith($filter, $sort, $col)
    {
        if($sort == "desc")
        {
            if($filter == 'all')
            {
                $customers = Customer::orderBy($col, 'DESC')->get();
            }
        }
        else if($sort == "asc")
        {
            if($filter == 'all')
            {
                $customers = Customer::orderBy($col, 'ASC')->get();
            }
        }

        return $customers;
    }

    public function toAdd()
    {
        // 获取所有项目经理
        $pms = User::where('role', '=', '项目经理')->get();
        // 获取所有项目来源
        $project_sources = Project_source::all();

        return view('manager.customer_add')
            ->with('pms', $pms)
            ->with('project_sources', $project_sources);
    }

    public function add(Request $request)
    {
        // 增加一条客户记录
        Customer::create($request->all());
        // 若项目来源表中没有新增项目来源，则增加一条记录
        Project_source::firstOrCreate(['source' => $request->input('source', '')]);

        // ajax
        $m3_result = new M3Result();
        $m3_result->status = 0;
        $m3_result->message = '添加成功';

        return $m3_result->toJason();
    }
}

