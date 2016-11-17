<?php
/**
 * Created by PhpStorm.
 * User: lam
 * Date: 16-10-23
 * Time: 上午10:43
 */

namespace App\Http\Controllers\admin\manager;


use App\Entity\Customer;
use App\Entity\Project_source;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCustomerRequest;
use App\Models\M3Result;
use App\Permission;
use Illuminate\Http\Request;
use App\Role;
use App\User;
use DB;
use Carbon\Carbon;
use Excel;
use Illuminate\Support\Facades\Log;
use Entrust;

class CustomerController extends Controller
{
    // 列出所有客户
    public function index(Request $request)
    {
        // 获取是否打印
        $export = $request->input('export', '');
        // 获取作为排序的列和顺序
        $col = $request->input('col', 'id');
        $sort = $request->input('sort', 'asc');

        $customers = $this->getCustomers();

        // 用户点击“查询”
        // 获得搜索的筛选器和值
        // 根据筛选器和筛选值筛选出结果
        $filter_name = $request->input('filter_name', null);
        $filter_value = $request->input('filter_value', '');
        
        if(!$request->get('reset'))
        {
            $customers = $this->sortWith($sort, $col, $filter_name, $filter_value);
        }

        foreach ($customers as $customer)
        {
            $customerManagers = array();
            $viewPermission = 'view_' . $customer->id . '_customer_information';
            $modifyPermission = 'modify_' . $customer->id . '_customer_information';

            $users = User::all();
            foreach ($users as $user)
            {
                if ($user->can([$viewPermission, $modifyPermission]))
                {
                    $customerManagers[] = $user;
                }
            }

            $customer->customerManagers = $customerManagers;
        }

        // 导出excel表
        if($export != null && $export === 'true')
        {
            $this->derivedExcel($customers);
        }

        return view('admin.manager.customers.customer_index')
            ->with('sort', $sort)
            ->with('filter_name', $filter_name)
            ->with('query_value', $request->get('value'))
            ->with('customers', $customers);
    }

    // 根据登录用户的权限来获取客户
    public function getCustomers()
    {
        // 判断身份
        if (Entrust::hasRole('admin'))
        {
            // 若是超级管理员，提取所有的客户
            $customers = Customer::all();
        }
        else
        {
            $customers = array();
            // 若是客户经理，先提取出所有客户
            $allCustomers = Customer::all();
            // 根据根据客户id，查看是否有权限查看客户
            foreach ($allCustomers as $thisCustomer)
            {
                // 若有，将客户push进客户数组中
                $viewPermission = 'view_' . $thisCustomer->id . '_customer_information';
                if (Entrust::can($viewPermission))
                {
                    $customers[] = $thisCustomer;
                }
            }
        }

        return $customers;
    }

    // 客户排序
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

    // 导出Excel表
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

    // 增加客户表单
    public function create()
    {
        $project_sources = Project_source::all();
        $users = \App\User::all();

        return view('admin.manager.customers.customer_add')
            ->with('project_sources', $project_sources)
            ->with('users', $users);
    }

    // 增加客户
    public function store(CreateCustomerRequest $request)
    {
        // 增加客户
        $customer = Customer::create($request->all());

        $customerManager_ids = $request->input('customerManagers');

        // 创造查看和修改该客户的权限
        $permissions = $this->createPermissions($customer);

        foreach ($customerManager_ids as $customerManager_id)
        {
             // 为选中的用户创造一个客户经理角色
            $user = User::where('id', '=', $customerManager_id)->first();
            if ( $user->hasRole('customerManager_' . $user->id))
            {
                $customerManagerRole = Role::where('name', '=', 'customerManager_' . $user->id)->first();
            }
            else
            {
                $customerManagerRole = new Role();
                $customerManagerRole->name = 'customerManager_' . $user->id;
                $customerManagerRole->display_name = '客户经理' . $user->name;
                $customerManagerRole->description = "客户经理可以查看修改客户资料";
                $customerManagerRole->save();
                $user->attachRole($customerManagerRole);
            }
            $customerManagerRole->attachPermissions($permissions);
        }

        $result['status'] = 0;
        $result['message'] = '添加成功';

        return $result;
    }

    // 创造查看和修改该客户的权限
    public function createPermissions($customer)
    {
        // 创造一个可以查看该客户的权限
        $viewCustomerInfo = new Permission();
        $viewCustomerInfo->name = 'view_' . $customer->id . '_customer_information';
        $viewCustomerInfo->display_name = '查看客户' . $customer->name;
        $viewCustomerInfo->description = '查看客户' . $customer->name . "的权限";
        $viewCustomerInfo->save();

        // 创造一个可以修改该客户的权限j
        $modifyCustomerInfo = new Permission();
        $modifyCustomerInfo->name = 'modify_' . $customer->id . '_customer_information';
        $modifyCustomerInfo->display_name = '修改客户' . $customer->name;
        $modifyCustomerInfo->description = '修改客户' . $customer->name . '的权限';
        $modifyCustomerInfo->save();

        $permissions = array($viewCustomerInfo, $modifyCustomerInfo);

        return $permissions;
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

