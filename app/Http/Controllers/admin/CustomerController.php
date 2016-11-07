<?php
/**
 * Created by PhpStorm.
 * User: lam
 * Date: 16-10-23
 * Time: 上午10:43
 */

namespace App\Http\Controllers\admin;


use App\Entity\Customer;
use App\Entity\Project_source;
use App\Entity\User;
use App\Http\Controllers\Controller;
use App\Models\M3Result;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use Excel;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $customers = Customer::all();
        $pms = User::where('role', '=', '项目经理')->get();
        $project_sources = Project_source::all();
        $export = $request->input('export', '');

        // 获取作为排序的列和顺序
        $col = $request->input('col', 'id');
        $sort = $request->input('sort', 'asc');
        // 用户点击“查询”
        // 获得搜索的筛选器和值
        // 根据筛选器和筛选值筛选出结果
        $filter_name = $request->input('filter_name', null);
        $filter_value = $request->input('filter_value', '');
        
        if(!$request->get('reset'))
        {
            $customers = $this->sortWith($sort, $col, $filter_name, $filter_value);
        }

        // 导出excel表
        if($export != null && $export === 'true')
        {
            $this->derivedExcel($customers);
        }

        return view('manager.customer_list')
            ->with('sort', $sort)
            ->with('filter_name', $filter_name)
            ->with('query_value', $request->get('value'))
            ->with('pms', $pms)
            ->with('customers', $customers)
            ->with('project_sources', $project_sources);
    }
    
    public function sortWith($sort, $col, $filter_name, $filter_value)
    {
        if($sort == "desc")
        {
           if($filter_value == "")
           {

               return Customer::orderBy($col, 'desc')->get();
           }
           return Customer::where($filter_name, '=', $filter_value)
                ->orderBy($col, 'desc')
                ->get();
        }
        else if($sort == "asc")
        {
            if($filter_name == "")
            {
                return Customer::orderBy($col, 'asc')->get();
            }
            return $customers =  Customer::where($filter_name, '=', $filter_value)
                ->orderBy($col, 'asc')
                ->get();
        }
    }

    public function derivedExcel($customers)
    {
        $data = array();

        $title = ['客户ID', '客户名称', '客户公司',
            '联系方式', '介绍', '状态', '添加时间', '优先级'];

        array_push($data, $title);

        foreach ($customers as $customer)
        {
            $customer_data = array();
            array_push($customer_data, $customer->id, $customer->name, $customer->company,
                $customer->phone, $customer->description, $customer->status,
                $customer->created_at, $customer->priority);
            array_push($data, $customer_data);
        }
        $footer = ['注意：优先级的值2,1,0分别代表：高，中，低'];

        array_push($data, $footer);
        $filename = Carbon::now()->toDateString() . "客户表";
        Excel::create($filename, function ($excel) use ($data){
            $excel->sheet('score', function($sheet) use ($data){
                $sheet->rows($data);
            });
        })->export('xls');
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

        return $m3_result->toJson();
    }

    public function delete(Request $request)
    {
        $id = $request->input('id', '');
        Customer::findOrFail($id)->delete();

        return redirect('manager/customer_list');
    }

    public function getCustomerDetails(Request $request)
    {
        $customer_id = $request->input('customer_id', null);
        if($customer_id == null)
        {
            return;
        }
        $customer = Customer::findOrFail($customer_id);
        $pms = User::where('role', '=', '项目经理')->get();
        $project_sources = Project_source::all();

        return view('manager.customer_details')
            ->with('customer', $customer)
            ->with('pms', $pms)
            ->with('project_sources', $project_sources);
    }

    public function update(Request $request)
    {
        // 若项目来源表中没有新增项目来源，则增加一条记录
        Project_source::firstOrCreate(['source' => $request->input('source', '')]);

        $customer_id = $request->input('customer_id', '');
        $name = $request->input('name', '');
        $company = $request->input('company', '');
        $phone = $request->input('phone', '');
        $description = $request->input('desc', '');
        $status = $request->input('status', '');
        $priority = $request->input('priority', '');
        $source = $request->input('source', '');
        $principal = $request->input('principal', '');
        $new_customer = Customer::findOrFail($customer_id);

        $new_customer->name = $name;
        $new_customer->company = $company;
        $new_customer->phone = $phone;
        $new_customer->description = $description;
        $new_customer->source = $source;
        $new_customer->principal = $principal;
        $new_customer->status = $status;
        $new_customer->priority = $priority;

        $new_customer->save();

        $m3_result = new M3Result();
        $m3_result->status = 0;
        $m3_result->message = "添加成功";

        return $m3_result->toJson();
    }
}

