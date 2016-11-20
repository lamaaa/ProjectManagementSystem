@extends('admin.manager.master')
<link rel="stylesheet" href="{{asset('/css/validform.css')}}">
@section('content')
    <div class="page-container">
            <div id="tabs" class="tabs mb-20">
                <div id="tabbar_content" class="tabBar cl">
                    <span id="span_info" class="clicked_span" onclick="info_tab(this);"
                          style="cursor:pointer;">客户信息</span>
                    <span class="normal_span" id="span_project"
                          onclick="project_tab(this);" style="cursor:pointer;">相关项目</span>
                    <span class="normal_span" id="span_log" onclick="log_tab(this);" style="cursor:pointer;">日志</span>
                </div>
            </div>
            <div id="tab01" class="tabcon" style="display: block ;">

                <form action="" method="post" class="form form-horizontal mt-15" id="form-customer-content-edit"
                      style="display: none">
                    <div class="row cl" style="height: 38px">
                        <label class="form-label col-sm-2"><span class="c-red"></span>客户ID:</label>
                        <div class="formControls col-sm-6">
                            <input id="input_id" type="text" name="id" readonly value="{{$customer->id}}" datatype="*">
                        </div>
                        <div class="Validform_checktip"></div>
                    </div>
                    <div class="row cl">
                        <label class="form-label col-sm-2"><span class="c-red"></span>客户名称:</label>
                        <div class="formControls col-sm-6">
                            <input id="input_name" type="text" name="name" value="{{$customer->name}}"
                                   class="input-text" datatype="*">
                        </div>
                        <div class="Validform_checktip"></div>
                    </div>
                    <div class="row cl" style="height: 38px">
                        <label class="form-label col-sm-2"><span class="c-red"></span>客户公司:</label>
                        <div class="formControls col-sm-6">
                            <input id="input_company" name="company" type="text"
                                   value="{{$customer->company}}" class="input-text" datatype="*">
                        </div>
                        <div class="Validform_checktip"></div>
                    </div>
                    <div class="row cl" style="height: 38px">
                        <label class="form-label col-sm-2"><span class="c-red"></span>联系方式:</label>
                        <div class="formControls col-sm-6">
                            <input type="text" name="phone" value="{{$customer->phone}}" class="input-text" datatype="*">
                        </div>
                        <div class="Validform_checktip"></div>
                    </div>
                    <div class="row cl" style="height: 38px">
                        <label class="form-label col-sm-2"><span class="c-red"></span>客户介绍:</label>
                        <div class="formControls col-sm-6">
                            <textarea type="textarea" class="textarea" id="description" name="customer_desc" datatype="*">{{$customer->description}}
                            </textarea>
                        </div>
                        <div class="Validform_checktip"></div>
                    </div>
                    <div class="row cl" style="height: 38px">
                        <label class="form-label col-sm-2"><span class="c-red"></span>客户经理:</label>
                        <div class="formControls col-sm-6">
                            {{--超级管理员不作为客户经理的候选项--}}
                            @foreach($users as $user)
                                @if ($user->hasRole('commonUser'))
                                    <input type="checkbox" name="customerManagers"
                                           @if (in_array($user, $customer->customerManagers)) checked="checked" @endif
                                           value="{{$user->id}}">
                                    {{$user->name}}&nbsp&nbsp&nbsp
                                @endif
                            @endforeach
                        </div>
                    </div>
                </form>

                <div class="row cl">
                    <div class="col-sm-8">
                        <button onclick="save_customer_details(this);"
                                class="btn btn-primary radius mt-15 ml-10 mb-20" id="save-btn">编辑</button>
                    </div>
                </div>

                <table class="table table-border table-bordered table-hover table-bg table-sort"
                       id="form-customer-content">
                    <thead>
                    <tr class="text-c">
                        <th width="30%" onclick="name_sort();">客户</th>
                        <th width="70%" onclick="company_sort();">值</th>
                    </tr>
                    </thead>
                    @if ($customer)
                        <tbody>
                        <tr class="text-c">
                            <td>客户ID</td>
                            <td>{{$customer->id}}</td>
                        </tr>

                        <tr class="text-c">
                            <td>客户名称</td>
                            <td>{{$customer->name}}</td>
                        </tr>

                        <tr class="text-c">
                            <td>客户公司</td>
                            <td>{{$customer->company}}</td>
                        </tr>

                        <tr class="text-c">
                            <td>联系方式</td>
                            <td>{{$customer->phone}}</td>
                        </tr>

                        <tr class="text-c">
                            <td>客户介绍</td>
                            <td>{{$customer->description}}</td>
                        </tr>

                        <tr class="text-c">
                            <td>客户经理</td>
                            <td>
                                @foreach($customer->customerManagers as $customerManager)
                                    {{$customerManager->name}}
                                @endforeach
                            </td>
                        </tr>

                        </tbody>
                    @endif
                </table>
            </div>
        <div id="form-errors"></div>
            {{--<div id="tab02" class="tabcon" style="display:none;">--}}
                {{--<table class="table table-border table-bordered table-hover table-bg table-sort">--}}
                    {{--<thead>--}}
                    {{--<tr class="text-c">--}}

                        {{--<th width="50" onclick="name_sort();">项目ID</th>--}}
                        {{--<th width="60" onclick="company_sort();">项目名称</th>--}}
                        {{--<th width="60" onclick="company_sort();">面积</th>--}}
                        {{--<th width="50" onclick="source_sort();">地址</th>--}}
                        {{--<th width="40" onclick="time_sort();">报价</th>--}}
                    {{--</tr>--}}
                    {{--</thead>--}}
                    {{--<tbody>--}}
                    {{--@foreach($projects as $item)--}}
                        {{--<tr class="text-c">--}}
                            {{--<td>{{$item->id}}</td>--}}
                            {{--<td>{{$item->name}}</td>--}}
                            {{--<td>{{$item->area}}</td>--}}
                            {{--<td>{{$item->address}}</td>--}}
                            {{--<td>{{$item->quote}}</td>--}}
                        {{--</tr>--}}
                    {{--@endforeach--}}
                    {{--</tbody>--}}
                {{--</table>--}}
            {{--</div>--}}
            {{--<div id="tab03" class="tabcon" style="display:none;">--}}
                {{--<div class="cl pd-5 bg-1 bk-gray">--}}
                     {{--<span class="l">--}}
                        {{--@if($from === "project_list")--}}
                             {{--<button--}}
                                 {{--style="width: 90px ; height: 40px;background-color: white ; margin: 10px 0 0 88%;color:white; line-height: 40px;text-align: center;visibility: hidden">--}}
                        {{--</button>--}}
                         {{--@else--}}
                             {{--<button onclick="add_log();" class="btn btn-primary radius">--}}
                            {{--<i class="Hui-iconfont">&#xe600;</i> 添加--}}
                        {{--</button>--}}
                         {{--@endif--}}
                    {{--</span>--}}
                {{--</div>--}}

                {{--<table class="table table-border table-bg">--}}
                    {{--<thead>--}}
                    {{--<tr class="text-c">--}}
                        {{--<!-- <th width="10%" onclick="name_sort();">序号</th> -->--}}
                        {{--<th width="10%" onclick="company_sort();">提交人</th>--}}
                        {{--<th width="10%" onclick="company_sort();">提交时间</th>--}}
                        {{--<th width="70%" onclick="source_sort();">内容</th>--}}
                        {{--<th width="10%" onclick="source_sort();">操作</th>--}}
                    {{--</tr>--}}
                    {{--</thead>--}}
                    {{--<tbody>--}}
                    {{--@foreach($logs as $item)--}}
                        {{--<tr class="text-c">--}}
                        {{--<!-- <td>{{$item->id}}</td> -->--}}
                            {{--<td>{{$item->submitter}}</td>--}}
                            {{--<td>{{$item->created_at}}</td>--}}
                            {{--<td style="white-space: pre-wrap">{{$item->content}}</td>--}}
                            {{--<td><a title=""--}}
                                   {{--onclick="customer_log_edit({{$item->id}});"--}}
                                   {{--class="ml-5"--}}
                                   {{--style="text-decoration:none;color: #0a6999">编辑</a>--}}
                                {{--@if($role_now == "管理员")--}}
                                    {{--<a title=""--}}
                                       {{--onclick="delete_customer_log({{$item->id}});"--}}
                                       {{--class="ml-5"--}}
                                       {{--style="text-decoration:none;color: #c00">删除</a>--}}
                                {{--@endif--}}
                            {{--</td>--}}
                        {{--</tr>--}}
                    {{--@endforeach--}}
                    {{--</tbody>--}}
                {{--</table>--}}
            {{--</div>--}}
    </div>

@endsection
@section('my-js')
    <script>

        $(".form").Validform({
            tiptype:2
        });
        $('#save-btn').click(function(){
            $(this).parent().addClass('col-sm-offset-2')
        })

        function customer_log_edit(id) {
            var index = layer.open({
                type: 2,
                title: "修改log",
                content: "edit_customer_log?id=" + id,
                area: ['80%', '70%']
            });
        }

        function delete_customer_log(id) {
            if (window.confirm('确认删除吗？')) {
                $.ajax({
                    type: 'post', // 提交方式 get/post
                    url: 'delete_customer_log', // 需要提交的 url
                    dataType: 'json',
                    data: {
                        id: id,
                        _token: "{{csrf_token()}}"
                    },
                    success: function (data) {
                        if (data == null) {
                            layer.msg('服务端错误', {icon: 2, time: 2000});
                            return;
                        }
                        if (data.status != 0) {
                            layer.msg(data.message, {icon: 2, time: 2000});
                            return;
                        }
                        layer.msg("删除成功", {icon: 1, time: 2000});
                        window.location.href="customer_content?customer_name={{$customer ? $customer->name : ''}}&log_tab=1";
                    },
                    error: function (xhr, status, error) {
                        layer.msg('ajax error', {icon: 2, time: 2000});
                    },
                    beforeSend: function (xhr) {
                        layer.load(0, {shade: false});
                    }
                });
            }
        }

        var priority_default = "{{ $customer ? $customer->priority : ''}}";
        $("#customer_select_priority option[value='" + priority_default + "']").attr("selected", true);

        var status_default = "{{ $customer ? $customer->status : ''}}";
        $("#customer_select_status option[value='" + status_default + "']").attr("selected", true);

        var customerManager_default = "{{ $customer ? $customer->customerManager : ''}}";
        $("#customerManager_select option[value='" + customerManager_default + "']").attr("selected", true);

        var source_default = "{{ $customer ? $customer->source : ''}}";
        $("#select_source option[value='" + source_default + "']").attr("selected", true);

        var source = $('select[name=source] option:selected').val();

        function source_change() {
            // 如果其他（自定义）选项被选中，将生成空白输入框
            if($('#other').is(':selected'))
            {
                $('#customer_input_source').tagEditor({
                    autocomplete:{
                        delay: 0,
                        position: {collision: 'flip'}
                    },
                    maxTags: 1,
                    forceLowercase: false,
                    placeholder: ''
                });
            }
            // 否则隐藏
            else
            {
                $('.tag-editor').hide();
            }
        }

        var tab01 = document.getElementById("tab01");
        var tab02 = document.getElementById("tab02");
        var tab03 = document.getElementById("tab03");
        var tabbar_content = document.getElementById("tabbar_content");
        function info_tab(obj) {
            document.getElementById("span_project").className = "normal_span";
            document.getElementById("span_log").className = "normal_span";
            document.getElementById("span_info").className = "normal_span";
            obj.className = "clicked_span";
            tab01.style.display = "block";
            tab02.style.display = "none";
            tab03.style.display = "none";
        }
        function project_tab(obj) {

            document.getElementById("span_project").className = "normal_span";
            document.getElementById("span_log").className = "normal_span";
            document.getElementById("span_info").className = "normal_span";

            obj.className = "clicked_span";
            tab02.style.display = "block";
            tab01.style.display = "none";
            tab03.style.display = "none";
        }
        function log_tab(obj) {
            document.getElementById("span_project").className = "normal_span";
            document.getElementById("span_log").className = "normal_span";
            document.getElementById("span_info").className = "normal_span";
            obj.className = "clicked_span";
            tab01.style.display = "none";
            tab02.style.display = "none";
            tab03.style.display = "block";
        }

        {{--//添加 log--}}
        {{--function add_log() {--}}
            {{--var index = layer.open({--}}
                {{--type: 2,--}}
                {{--title: "添加 log",--}}
                {{--content: "customer_add_log?id=" + "{{$id}}",--}}
                {{--area: ['80%', '70%']--}}
            {{--});--}}
        {{--}--}}

        function save_customer_details(obj) {
            var content = obj.innerHTML;

            if (content == "编辑") {
                obj.innerHTML = "保存";

                document.getElementById("form-customer-content").style.display = "none";
                document.getElementById("form-customer-content-edit").style.display = "block";
                $(this).parent().addClass('.col-sm-offset-2');

            } else if (content == "保存") {
                var status = $('select[name=status] option:selected').val();
                var priority = $('select[name=priority] option:selected').val();
                var name = $('input[name=name]').val();
                var company = $('input[name=company]').val();
                var phone = $('input[name=phone]').val();
                var description = $('input[name=description]').val();
                var id = $('input[name=id]').val();

                // 获取项目经理
                var customerManagers = new Array();
                $('input[name=customerManagers]:checked').each(function(){
                    customerManagers.push(this.value);
                });

                $(this).parent().addClass('.col-sm-offset-2');
                if (name == "" || company == "" || phone == "" ||
                        status == "" || priority == "" || customerManagers.length == 0
                )
                {
                    alert("对不起,信息没有填写完整");
                    return;
                }

                if (status == "请选择" ||
                        priority == "请选择")
                {
                    alert("对不起,信息没有填写完整");
                    return;
                }

                //在提交包含 tag 的表单的时候,如果 tag 为空,就会有问题

                $('#form-customer-content-edit').ajaxSubmit({
                    type: 'PUT', // 提交方式 get/post
                    url: '/manager/customer/{{$customer->id}}', // 需要提交的 url
                    dataType: 'json',
                    data: {
                        id: '{{$customer->id}}',
                        name: name,
                        company: company,
                        phone: phone,
                        description: description,
                        customerManagers: customerManagers,
                        status: status,
                        priority: priority,
                        _token: "{{csrf_token()}}"
                    },
                    success: function (data) {
                        if (data == null) {
                            layer.msg('服务端错误', {icon: 2, time: 2000});
                            return;
                        }
                        if (data.status != 0) {
                            layer.msg(data.message, {icon: 2, time: 2000});
                            return;
                        }
                        layer.msg(data.message, {icon: 1, time: 2000});
                        parent.location.reload();
                    },
                    error: function (data) {
                        if( data.status === 422 ) {
                            //process validation errors here.
                            var errors = errors = $.parseJSON(data.responseText); //this will get the errors response data.

                            errorsHtml = '<div class="alert alert-danger"><ul>';

                            $.each( errors, function( key, value ) {
                                errorsHtml += '<li>' + value[0] + '</li><br>'; //showing only the first error.
                            });
                            errorsHtml += '</ul></div>';

                            $( '#form-errors' ).html( errorsHtml ); //appending to a <div id="form-errors"></div> inside form
                        }
                    },
                });
            }

        }
        $(document).ready(function() {
            @if (Request::get('log_tab'))
                $('#span_log').trigger('click');
            @endif
            @if (Request::get('project_tab'))
                $('#span_project').trigger('click');
            @endif
        })
    </script>
@endsection