@extends('master')

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
                <label class="form-label col-sm-2"><span class="c-red"></span>项目经理:</label>
                <div class="formControls col-sm-4">
                    <select name="principal" class="select" id="" datatype="*">
                        <option value="请选择">请选择</option>
                        @foreach($pms as $pm)
                            <option value="{{$pm->name}}">
                                {{$pm->name}}
                            </option>
                        @endforeach
                    </select>
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
                                <option value="{{$project_source->source}}">{{$project_source->source}}</option>
                        @endforeach
                        <option value="other" id="other">其他(自定义)</option>
                    </select>
                    <input id="customer_input_source" name="customer_input_source" type="text" value=""
                           style="display: none">
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

        function add_customer_query()
        {
            var source = $('#select_source option:selected').val();
            if(source == 'other')
            {
                source = $('input[name=customer_input_source]').val();
            }
            if ($('input[name=name]').val() == "" ||
                    $('input[name=company]').val() == "" ||
                    $('input[name=phone]').val() == "" ||
                    $('textarea[name=description]').val() == "" ||
                    $('input[name=principal]').val() == "" ||
                    $('input[name=status]').val() == "" ||
                    $('input[name=priority]').val() == ""
            )
            {
                alert("对不起,信息没有填写完整");
                return;
            }

            var principal = $('select[name=principal] option:selected').val();
            var status = $('select[name=status] option:selected').val();
            var priority = $('select[name=priority] option:selected').val();

            if (principal == "请选择" ||
                    status == "请选择" ||
                    priority == "请选择" ||
                    source == "请选择")
            {
                alert("对不起,信息没有填写完整");
                return;
            }

            $('#form-customer-add').ajaxSubmit({
                type: 'post', // 提交方式 get/post
                url: '/manager/customer_add', // 需要提交的 url
                dataType: 'json',
                data: {
                    name: $('input[name=name]').val(),
                    company: $('input[name=company]').val(),
                    phone: $('input[name=phone]').val(),
                    description: $('input[name=description]').val(),
                    source: source,
                    principal: principal,
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
                error: function (xhr, status, error) {
                    console.log(xhr);
                    console.log(status);
                    console.log(error);
                    layer.msg('ajax error', {icon: 2, time: 2000});
                },
            });

        }
    </script>
@endsection