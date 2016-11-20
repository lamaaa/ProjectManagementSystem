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
use App\Http\Requests\CreateAndUpdateCustomerRequest;
use App\Models\M3Result;
use App\Permission;
use Illuminate\Http\Request;
use App\Role;
use App\User;
use DB;
use Carbon\Carbon;
use Excel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Entrust;
use Illuminate\Database\Eloquent\Collection;

class CustomerController extends Controller
{
    // 列出所有客户
    public function index(Request $request)
    {
        $user = Auth::user();
        // 获取是否打印
        $export = $request->input('export', '');
        // 获取作为排序的列和顺序
        $col = $request->input('col', 'id');
        $sort = $request->input('sort', 'asc');

        $customers = $this->getCustomers($user);
        $customerManagers = $this->getAllCustomerManagers();
        // 用户点击“查询”
        // 获得搜索的筛选器和值
        // 根据筛选器和筛选值筛选出结果
        $filter_name = $request->input('filter_name', null);
        $filter_value = $request->input('filter_value', '');
        
        if(!$request->get('reset'))
        {
            $customers = $this->sortWith($sort, $col, $filter_name, $filter_value);
        }

        if ($customers != null)
        {
            $this->attachCustomerManagers($customers);
        }
        // 导出excel表
        if($export != null && $export === 'true')
        {
            $this->derivedExcel($customers);
        }

        return view('admin.manager.customers.customer_index')
            ->with('sort', $sort)
            ->with('filter_name', $filter_name)
            ->with('query_value', $filter_value)
            ->with('customerManagers', $customerManagers)
            ->with('customers', $customers);
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
    public function store(CreateAndUpdateCustomerRequest $request)
    {
        // 增加客户
        $customer = Customer::create($request->all());

        $customerManager_ids = $request->input('customerManagers');

        // 创造查看和修改该客户的权限
        $permissions = $this->createViewAndModifyPermissions($customer);

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

    // 删除客户
    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        // 删除该客户对应的查看和修改权限
        $this->deleteViewAndModifyPermissions($customer);
        $customer->delete();

        return redirect('/manager/customer');
    }

    // 展示客户详情
    public function show($id)
    {
        if ($id == null)
        {
            return;
        }
        $customer = Customer::findOrFail($id);
        $this->attachCustomerManagers($customer);
        $users = User::all();

        return view('admin.manager.customers.customer_show')
            ->with('users', $users)
            ->with('customer', $customer);

    }

    // 更新客户资料
    public function update(CreateAndUpdateCustomerRequest $request)
    {
        $id = $request->input('id');
        $name = $request->input('name', '');
        $company = $request->input('company', '');
        $phone = $request->input('phone', '');
        $description = $request->input('desc', '');
        $newCustomerManager_ids = $request->input('customerManagers');

        Customer::where('id', '=', $id)
            ->update([
                'name' => $name,
                'company' => $company,
                'phone' => $phone,
                'description' => $description,
            ]);

        $customer = Customer::findOrFail($id);
        $this->updateCustomerManagers($customer, $newCustomerManager_ids);

        $result['status'] = 0;
        $result['message'] = '更新成功';

        return $result;
    }

    public function updateCustomerManagers($customer, $newCustomerManager_ids)
    {
        $this->attachCustomerManagers($customer);
        $customerManagers = $customer->customerManagers;
        $newCustomerManagers = array();
        foreach ($newCustomerManager_ids as $newCustomerManager_id)
        {
            $newCustomerManagers[] = User::findOrFail($newCustomerManager_id);
        }
        // 找出新老客户经理的差异
        $deletedCustomerManagers = array_diff($customerManagers, $newCustomerManagers);
        $addedCustomerManagers = array_diff($newCustomerManagers, $customerManagers);
        $customerManagers_diff = array_merge($deletedCustomerManagers, $addedCustomerManagers);

        foreach ($customerManagers_diff as $customerManager)
        {
            // 获得该权限
            $permissions = $this->getViewAndModifyPermissions($customer);
            $customerManagerRole = Role::where('name', '=', 'customerManager_' . $customerManager->id)->first();
            if (in_array($customerManager, $customerManagers))
            {
                // detach该角色的该权限
                $customerManagerRole->detachPermissions($permissions);
            }
            else
            {
                // attach该角色的该权限
                $customerManagerRole->attachPermissions($permissions);
            }
        }
    }

    // 获取所有的客户经理
    public function getAllCustomerManagers()
    {
        $customerManagers = array();
        $users = User::all();

        foreach ($users as $user)
        {
            if ($user->hasRole('customerManager_*'))
            {
                $customerManagers[] = $user;
            }
        }

        return $customerManagers;
    }

    // 根据用户的权限来获取客户
    public function getCustomers($user)
    {
        // 判断身份
        if ($user->hasRole('admin'))
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
                $modifyPermission = 'modify_' . $thisCustomer->id . '_customer_information';
                if ($user->can([$viewPermission, $modifyPermission]))
                {
                    $customers[] = $thisCustomer;
                }
            }
        }

        return $customers;
    }

    // 为各个客户附上对应客户的客户经理
    public function attachCustomerManagers($customer)
    {
        if ((!$customer instanceof Collection) && (!is_array($customer)))
        {
            $customer = collect([$customer]);
        }
        foreach ($customer as $thisCustomer)
        {
            $customerManagers = array();
            $viewPermission = 'view_' . $thisCustomer->id . '_customer_information';
            $modifyPermission = 'modify_' . $thisCustomer->id . '_customer_information';
            $users = User::all();
            foreach ($users as $user)
            {
                if ($user->can([$viewPermission, $modifyPermission]))
                {
                    $customerManagers[] = $user;
                }
            }
            $thisCustomer->customerManagers = $customerManagers;
        }
    }

    // 客户排序
    public function sortWith($sort, $col, $filter_name, $filter_value)
    {
        if ($filter_name == "customerManager")
        {
            $user = User::where('id', '=', $filter_value)->first();

            return $this->getCustomers($user);
        }
        // 如果筛选值为空，那么直接列出全部客户
        else if ($filter_value == "")
        {
            return Customer::orderBy($col, $sort)->get();
        }
        return Customer::where($filter_name, '=', $filter_value)
            ->orderBy($col, $sort)
            ->get();
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

    // 创造查看和修改该客户的权限
    public function createViewAndModifyPermissions($customer)
    {
        // 创造一个可以查看该客户的权限
        $viewCustomerInfo = new Permission();
        $viewCustomerInfo->name = 'view_' . $customer->id . '_customer_information';
        $viewCustomerInfo->display_name = '查看客户' . $customer->name;
        $viewCustomerInfo->description = '查看客户' . $customer->name . "的权限";
        $viewCustomerInfo->save();

        // 创造一个可以修改该客户的权限
        $modifyCustomerInfo = new Permission();
        $modifyCustomerInfo->name = 'modify_' . $customer->id . '_customer_information';
        $modifyCustomerInfo->display_name = '修改客户' . $customer->name;
        $modifyCustomerInfo->description = '修改客户' . $customer->name . '的权限';
        $modifyCustomerInfo->save();

        $permissions = array($viewCustomerInfo, $modifyCustomerInfo);

        return $permissions;
    }

    // 删除权限
    public function deleteViewAndModifyPermissions($customer)
    {
        $permissions = $this->getViewAndModifyPermissions($customer);

        // 由于使用了级联删除，所以删除权限同时也删除了对应的permission_role关系
        foreach ($permissions as $permission)
        {
            $permission->delete();
        }
    }

    public function getViewAndModifyPermissions($customer)
    {
        $viewPermissionName = 'view_' . $customer->id . '_customer_information';
        $modifyPermissionName = 'modify_' . $customer->id . '_customer_information';

        $permissions = [Permission::where('name', '=', $viewPermissionName)->first(),
                        Permission::where('name', '=', $modifyPermissionName)->first()];

        return $permissions;
    }
}

