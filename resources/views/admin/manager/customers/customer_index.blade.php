@extends('admin.manager.master')

@section('content')
    <div class="page-container">
        <div class="cl pd-5 bg-1 bk-gray mb-20">
            <span class="l">
            <a href="javascript:;" onclick="customer_add('/manager/customer/create')" class="btn btn-success radius"><i
                        class="Hui-iconfont">&#xe600;</i> 添加客户</a>
            <a href="javascript:;" onclick="export_excel_all_customers()"
               class="btn btn-primary radius">导出列表(Excel)</a>
            <div style="margin-left: 300px;margin-top: -28px">
                {{--筛选器--}}
                <div class="display: inline" style="float: left">
                    <label for="select_customer_filter" style="margin-left: 23px">筛选条:</label>
                    <select name="" onchange="filter_change(this);" id="select_customer_filter" class="select"
                            style="width: 60%;margin-left: 15px;margin-top: -23px;float: left;margin-left: 100px;">
                        <option value="name" @if ($filter_name === 'name') selected="selected" @endif>客户名称</option>
                        <option value="status" @if ($filter_name === 'status') selected="selected" @endif>状态</option>
                        <option value="customerManager" @if ($filter_name === 'customerManager') selected="selected" @endif>客户经理</option>
                        <option value="priority" @if ($filter_name === 'priority') selected="selected" @endif>优先级</option>
                    </select>
                </div>

                {{--优先级--}}
                <div id="value_priority" class=""
                     style="height: 30px;display: inline;float: left; @if (isset($filter_name) && $filter_name === 'priority') display: block; @else display:none; @endif">
                    <select name="" id="value_priority_select" class="select" style="width: 160px;margin-left: 15px">
                        <option value="2" @if ($query_value == '2') selected="selected" @endif>高</option>
                        <option value="1" @if ($query_value == '1') selected="selected" @endif>中</option>
                        <option value="0" @if ($query_value == '0') selected="selected" @endif>低</option>
                    </select>
                </div>
                {{--进度--}}
                <div id="value_status" class=""
                     style="height: 30px;display: inline;float: left;@if (isset($filter_name) && $filter_name === 'status') display: block; @else display:none; @endif">
                    <select name="" id="value_status_select" class="select" style="width: 160px;margin-left: 15px">
                        <option value="未联系" @if ($query_value == '未联系') selected="selected" @endif>未联系</option>
                        <option value="沟通中" @if ($query_value == '沟通中') selected="selected" @endif>沟通中</option>
                        <option value="开发中" @if ($query_value == '开发中') selected="selected" @endif>开发中</option>
                        <option value="测试中" @if ($query_value == '测试中') selected="selected" @endif>测试中</option>
                        <option value="已完成" @if ($query_value == '已完成') selected="selected" @endif>已完成</option>
                    </select>
                </div>
                {{--项目经理--}}
                <div id="value_customerManager" class=""
                     style="height: 30px;display: inline;float: left;@if (isset($filter_name) && $filter_name === 'customerManager') display: block; @else display:none; @endif">
                    <select name="" id="value_customerManager_select" class="select" style="width: 160px;margin-left: 15px">
                        @foreach($customerManagers as $customerManager)
                            <option value="{{$customerManager->id}}" @if ($query_value == $customerManager->name) selected="selected" @endif>
                                {{$customerManager->name}}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div id="value_text" class="" style="height: 30px;display: inline;float: left;@if (isset($filter_name) && $filter_name !== 'name') display: none; @endif">
                    <input type="text" class="input input-text" style="width: 160px;margin-left: 15px" value="{{ $filter_name == 'name' && $query_value ? $query_value : '' }}"
                           id="value_text_input">
                </div>

                <a href="javascript:;" onclick="query_customer('query')" class="btn btn-success" id='start_query'
                   style="margin-left: 10px">查询</a>
                <a href="javascript:;" class="btn btn-primary" id='reset_query'
                   style="margin-left: 10px">重置</a>
            </div>
            <div id="sort_mark" hidden>{{$sort}}</div>
            </span>
        </div>

        <table class="table table-border table-bordered table-hover table-bg table-sort">
            <thead>
            <tr class="text-c">
                <th width="55" onclick="sort('id');">ID<i class="Hui-iconfont"
                            style="margin-left: 6px;cursor: pointer">
                        &#xe675;</i></th>
                <th width="">客户名称</th>
                <th width="">客户公司</th>
                <th width="">联系方式</th>
                {{--<th width="">来源</th>--}}
                <th width="">客户经理</th>
                <th width="">状态</th>
                <th width="90" onclick="sort('created_at');">添加时间<i class="Hui-iconfont"
                                                             style="margin-left: 6px;cursor: pointer">
                        &#xe675;</i></th>
                <th width="80" onclick="sort('priority');">优先级<i class="Hui-iconfont"
                                                                style="margin-left: 6px;cursor: pointer">
                        &#xe675;</i></th>
                <th width="">操作</th>
            </tr>
            </thead>
            <tbody>
            @foreach($customers as $customer)
                <tr class="text-c">
                    <td>{{$customer->id}}</td>
                    <td width="">
                        <a title="详情" href="javascript:;"
                           onclick="customer_details('客户详情','customer_details?customer_id={{$customer->id}}&from=customer_list')"
                           class="ml-5"
                           style="text-decoration:none;color: #5A98DE">{{$customer->name}}</a>
                    </td>
                    <td>{{$customer->company}}</td>
                    <td>{{$customer->phone}}</td>
                    <td>
                        @foreach($customer->customerManagers as $customerManager)
                            <div class="label label-secondary radius">{{$customerManager->name}}</div>
                        @endforeach
                    </td>
                    <td><span class="label label-success radius">{{$customer->status}}</span></td>
                    <td class="td_created_at">
                        {{$customer->created_at}}
                    </td>
                    <td>
                        @if(value($customer->priority) === 1)
                            中
                        @elseif(value($customer->priority) === 2)
                            高
                        @elseif(value($customer->priority) === 0)
                            低
                        @endif
                    </td>
                    <td>
                        <a href="javascript:;" onclick="delete_customer('/manager/customer_delete/?id={{$customer->id}}')"
               class="btn btn-danger radius">删除</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

@endsection

@section('my-js')
    <script type="text/javascript">
        var td_created_ats = document.getElementsByClassName('td_created_at');


        for (var i = 0; i < td_created_ats.length; i++) {
            var old_value = td_created_ats[i].innerHTML.toString();

            var new_val = old_value.substring(25, 35);

            var arr = old_value.match(/./g);
            td_created_ats[i].innerHTML = new_val;
        }

        $(".tag").each(function () {
            if (this.innerHTML == "") {
                this.style.backgroundColor = "white";
                this.style.height = "0px";
                this.style.width = "0px";
            } else {
                var length = this.innerHTML.length;
                this.style.width = length * 15 + "px";
            }
        });

        function get_filter_value(filter_name)
        {
            var value = "";

            switch (filter_name)
            {
                case "priority":
                    value = $('#value_priority_select option:selected').val();
                    break;
                case "status":
                    value = $('#value_status_select option:selected').val();
                    break;
                case "customerManager":
                    value = $("#value_customerManager_select option:selected").val();
                    break;
                case "name":
                    value = $('#value_text_input').val();
                    break;
            }

            return value;
        }

        function query_customer() {
            var filter_name = $('#select_customer_filter option:selected').val();
            var filter_value = get_filter_value(filter_name);
            location.href = "/manager/customer?filter_name=" + filter_name + "&filter_value=" + filter_value + "";
        }

        //导出客户列表
        function export_excel_all_customers() {
            location.href = "customer_list?export=true";
        }

        function filter_change(obj) {

            var value_priority = document.getElementById("value_priority");
            var value_text = document.getElementById("value_text");
            var value_status = document.getElementById("value_status");
            var value_customerManager = document.getElementById("value_customerManager");

            // 改变筛选器名字
            filter_name = obj.value;

            switch (filter_name) {
                case "priority":
                    value_priority.style.display = "block";
                    value_text.style.display = "none";
                    value_status.style.display = "none";
                    value_customerManager.style.display = "none";
                    break;
                case "status":
                    value_priority.style.display = "none";
                    value_text.style.display = "none";
                    value_status.style.display = "block";
                    value_customerManager.style.display = "none";
                    break;
                case "name":
                    value_priority.style.display = "none";
                    value_text.style.display = "block";
                    value_status.style.display = "none";
                    value_customerManager.style.display = "none";
                    break;
                case "customerManager":
                    value_priority.style.display = "none";
                    value_text.style.display = "none";
                    value_customerManager.style.display = "block";
                    value_status.style.display = "none";
                    break;
            }
        }

        function customer_details(title, url) {
            var index = layer.open({
                type: 2,
                title: title,
                content: url,
                area: ['100%', '100%']
            });

        }

        function customer_add(url) {
            var index = layer.open({
                type: 2,
                title: '添加客户',
                content: url,
                area: ['100%', '100%']
            });
        }

        function sort(orderBy) {
            var filter_name = $('#select_customer_filter option:selected').val();
            var filter_value = get_filter_value(filter_name);
            var sort_mark = document.getElementById("sort_mark").innerHTML;
            if (sort_mark == "desc") {
                location.href = "customer?col=" + orderBy + "&sort=asc&filter_name=" +
                        filter_name + "&filter_value=" +
                        filter_value;
            } else if (sort_mark == "asc" || sort_mark == "") {
                location.href = "customer?col=" + orderBy + "&sort=desc&filter_name=" +
                        filter_name + "&filter_value=" +
                        filter_value;
            }
        }

        function delete_customer(url)
        {
            if(window.confirm('确认删除该客户？'))
            {
                window.location.href = url;
            }
        }

        $(document).ready(function() {
            $("body").keydown(function() {
                if (event.keyCode == "13") {//keyCode=13是回车键
                    $('#start_query').trigger('click');
                }
            });
            $('#reset_query').click(function() {
                window.location.href = '/manager/customer?reset=1' ;
            })

        })

    </script>
@endsection


