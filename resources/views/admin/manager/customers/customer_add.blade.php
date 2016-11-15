@extends('admin.manager.master')
<link rel="stylesheet" href="{{asset('/css/validform.css')}}">
@section('content')
    <div class="page-container">
        <form action="" method="post" class="form form-horizontal" id="form-customer-add" data-toggle="validator">
            <div class="row cl" style="height: 38px">
                <label class="form-label col-sm-2"><span class="c-red"></span>客户名称:</label>
                <div class="formControls col-sm-4">
                    <input id="input_name" type="text" value="" name="name" class="input-text" datatype="*">
                </div>
                <div class="Validform_checktip"></div>
            </div>

            <div class="row cl" style="height: 38px">
                <label class="form-label col-sm-2"><span class="c-red"></span>客户公司:</label>
                <div class="formControls col-sm-4">
                    <input id="input_company" type="text" value="" name="company" class="input-text" datatype="*">
                </div>
                <div class="Validform_checktip"></div>
            </div>

            <div class="row cl" style="height: 38px">
                <label class="form-label col-sm-2"><span class="c-red"></span>联系方式:</label>
                <div class="formControls col-sm-4">
                    <input id="input_name" type="text" value="" name="phone" class="input-text" datatype="*">
                </div>
                <div class="Validform_checktip"></div>
            </div>

            <div class="row cl" style="height: 38px">
                <label class="form-label col-sm-2"><span class="c-red"></span>介绍:</label>
                <div class="formControls col-sm-4">
                    <textarea id="input_name" class="textarea" type="text" value="" name="description" datatype="*"></textarea>
                </div>
                <div class="Validform_checktip"></div>
            </div>

            <div class="row cl" style="height: 38px">
                <label class="form-label col-sm-2"><span class="c-red"></span>客户经理:</label>
                <div class="formControls col-sm-4">
                    {{--超级管理员不作为客户经理的候选项--}}
                    @foreach($users as $user)
                        @if ($user->hasRole('commonUser'))
                        <input type="checkbox" name="customerManagers" value="{{$user->name}}">
                            {{$user->name}}&nbsp&nbsp&nbsp
                        @endif
                    @endforeach
                </div>
                <div class="Validform_checktip"></div>
            </div>

            <div class="row cl" style="height: 38px">
                <label class="form-label col-sm-2"><span class="c-red"></span>来源:</label>
                <div class="formControls col-sm-4">
                    <select name="source" class="select" id="select_source"
                            onchange="source_change();" datatype="*">
                        <option value="请选择">请选择</option>
                        @foreach($project_sources as $project_source)
                            @if(value($project_source->source) == "")
                                <option value="{{$project_source->source}}">空</option>
                            @else
                                <option value="{{$project_source->source}}">{{$project_source->source}}</option>
                            @endif
                        @endforeach
                        <option value="other" id="other">其他(自定义)</option>
                    </select>
                            <input id="customer_input_source" type="text" value="" name="customer_input_source"
                                   class="input-text" datatype="*" style="display: none;">
                    <div class="Validform_checktip"></div>
                </div>
                <div class="Validform_checktip"></div>
            </div>

            <div class="row cl" style="height: 38px">
                <label class="form-label col-sm-2"><span class="c-red"></span>状态：</label>
                <div class="formControls col-sm-4">
                        <select name="status" class="select" id="customer_select_priority" datatype="*">
                            <option value="请选择">请选择</option>
                            <option value="未联系">未联系</option>
                            <option value="沟通中">沟通中</option>
                            <option value="开发中">开发中</option>
                            <option value="测试中">测试中</option>
                            <option value="已完成">已完成</option>
                        </select>
                    <div class="Validform_checktip"></div>
                </div>
            </div>

            {{--优先级--}}
            <div class="row cl" style="height: 38px">
                <label class="form-label col-sm-2"><span class="c-red"></span>优先级：</label>
                <div class="formControls col-sm-4">
                    <select name="priority" class="select" id="customer_select_priority" datatype="*">
                        <option value="请选择">请选择</option>
                        <option value="2">高</option>
                        <option value="1">中</option>
                        <option value="0">低</option>
                    </select>
                    <div class="Validform_checktip"></div>
                </div>
            </div>
        </form>

        <div class="row cl">
            <div class="col-sm-8 col-sm-offset-2">
                <button onclick="add_customer_query();" class="btn btn-secondary radius mt-15 ml-10">
                    保存
                </button>
            </div>
        </div>
        <br>
        <div id="form-errors"></div>
    </div>
@endsection
@section('my-js')
    <script>
        $(".form").Validform({
            tiptype:2
        });

        function source_change() {
            // 如果其他（自定义）选项被选中，将生成空白输入框
            if($('#other').is(':selected'))
            {
                $('#customer_input_source').show();
            }
            // 否则隐藏
            else
            {
                $('#customer_input_source').hide();
            }
        }

        function add_customer_query()
        {
            var source = $('#select_source option:selected').val();
            var status = $('select[name=status] option:selected').val();
            var priority = $('select[name=priority] option:selected').val();
            var name = $('input[name=name]').val();
            var company = $('input[name=company]').val();
            var phone = $('input[name=phone]').val();
            var description = $('input[name=description]').val();

            // 获取项目经理
            var customerManagers = new Array();
            $('input[name=customerManagers]:checked').each(function(){
                customerManagers.push(this.value);
            });

            // 获取来源
            if(source == 'other')
            {
                source = $('input[name=customer_input_source]').val();
            }

            if (name == "" || company == "" || phone == "" ||
                     status == "" || priority == ""
            )
            {
                alert("对不起,信息没有填写完整");
                return;
            }

            if (status == "请选择" ||
                    priority == "请选择" ||
                    source == "请选择")
            {
                alert("对不起,信息没有填写完整");
                return;
            }

            $('#form-customer-add').ajaxSubmit({
                type: 'POST', // 提交方式 get/post
                url: '/manager/customer', // 需要提交的 url
                dataType: 'json',
                data: {
                    name: name,
                    company: company,
                    phone: phone,
                    description: description,
                    source: source,
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
                }
            });
        }
    </script>
@endsection