@extends('master')

@section('content')

    <div class="page-container" style="margin-bottom: 150px">
        <form action="" method="post" class="form form-horizontal" id="form-project-add">
            {{--1.项目名称--}}
            <div class="row cl">
                <label class="form-label col-sm-2"><span class="c-red"></span>项目名称:</label>
                <div class="formControls col-sm-4">
                    <input id="input_name" type="text" name="project_name" value="" class="input-text" datatype="*">
                </div>
                <div class="Validform_checktip"></div>
            </div>

            {{--2.客户名称--}}
            <div class="row cl">
                <label class="form-label col-sm-2"><span class="c-red"></span>客户名称:</label>
                <div class="formControls col-sm-4">
                    <select name="customer" class="select" id="select_customer">
                        <option value="请选择">请选择</option>
                        @foreach($customers as $customer)
                            <option value="{{$customer->name}}">{{$customer->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{--3.客户需求--}}
            <div class="row cl">
                <label class="form-label col-sm-2"><span class="c-red"></span>客户需求:</label>
                <div class="formControls col-sm-4">
                    <textarea name="requirements" type="text" class="textarea" datatype="*"></textarea>
                </div>
                <div class="Validform_checktip"></div>
            </div>

            {{--<div class="row cl">--}}
                {{--<label class="form-label col-sm-2"><span class="c-red"></span>进度：</label>--}}
                {{--<div class="formControls col-sm-4">--}}
                    {{--<select name="status" class="select" id="select_status">--}}
                        {{--<option value="请选择">请选择</option>--}}
                        {{--<option value="0">未开始</option>--}}
                        {{--<option value="1">开发中</option>--}}
                        {{--<option value="2">测试中</option>--}}
                        {{--<option value="0">已完成</option>--}}
                    {{--</select>--}}
                {{--</div>--}}
            {{--</div>--}}

            {{--4.优先级--}}
            <div class="row cl">
                <label class="form-label col-sm-2"><span class="c-red"></span>优先级：</label>
                <div class="formControls col-sm-4">
                    <select name="priority" class="select" id="select_priority">
                        <option value="请选择">请选择</option>
                        <option value="2">高</option>
                        <option value="1">中</option>
                        <option value="0">低</option>
                    </select>
                </div>
            </div>

            {{--5.负责人 , 这里的负责人是项目经理--}}
            <div class="row cl">
                <label class="form-label col-sm-2"><span class="c-red"></span>项目经理:</label>
                <div class="formControls col-sm-4">
                    <select name="principal" class="select" id="select_principal">
                        <option value="请选择">请选择</option>
                        @foreach($pms as $pm)
                            <option value="{{$pm->name}}">{{$pm->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="checkbox-inline">
                    <input type="checkbox" id="inlineCheckbox1" value="option1"> 选项 1
                </label>
                <label class="checkbox-inline">
                    <input type="checkbox" id="inlineCheckbox2" value="option2"> 选项 2
                </label>
                <label class="checkbox-inline">
                    <input type="checkbox" id="inlineCheckbox3" value="option3"> 选项 3
                </label>
                <label class="checkbox-inline">
                    <input type="radio" name="optionsRadiosinline" id="optionsRadios3"
                           value="option1" checked> 选项 1
                </label>
                <label class="checkbox-inline">
                    <input type="radio" name="optionsRadiosinline" id="optionsRadios4"
                           value="option2"> 选项 2
                </label>
            </div>
            {{--6.截止时间--}}
            <div class="row cl">
                <label class="form-label col-sm-2"><span class="c-red"></span>预计完成时间:</label>
                <div class="formControls col-sm-4">
                    <input type="text" value="" name="completion_time" id="addProject_completion_time"
                           class="input-text" datatype="*">
                </div>
                <div class="Validform_checktip"></div>
            </div>

            {{--7.现报价--}}
            <div class="row cl">
                <label class="form-label col-sm-2"><span class="c-red"></span>现报价:</label>
                <div class="formControls col-sm-4">
                    <input id="input_quote" type="number" value="" name="quote" class="input-text" datatype="*" style="width: 100%;">
                </div>
                <div class="Validform_checktip"></div>
            </div>


        </form>

        <div class="row cl">
            <div class="col-sm-8 col-sm-offset-2">
                <button class="btn btn-secondary radius mt-15 ml-10" onclick="addProjectConfirm();">确认</button>
            </div>
        </div>
    </div>
@endsection

@section('my-js')
    <script type="text/javascript">
        $(".form").Validform({
            tiptype:2
        });

        $("#addProject_completion_time").jcDate({
            Default: '请选择日期',
            Class: "", //为input注入自定义的class类（默认为空）
            Event: "click", //设置触发控件的事件，默认为click
            Speed: 50,    //设置控件弹窗的速度，默认100（单位ms）
            Left: 0,       //设置控件left，默认0
            Top: 22,       //设置控件top，默认22
            Format: "-",   //设置控件日期样式,默认"-",效果例如：XXXX-XX-XX
            DoubleNum: false, //设置控件日期月日格式，默认true,例如：true：2015-05-01 false：2015-5-1
            Timeout: 100,   //设置鼠标离开日期弹窗，消失时间，默认100（单位ms）
            OnChange: function () { //设置input中日期改变，触发事件，默认为function(){}
                console.log('num change');
            }
        });

        function addProjectConfirm() {
            var quote = $('input[name=quote]').val();
            var project_name = $('input[name=project_name]').val();
            var customer = $('select[name=customer] option:selected').val();
            var principal = $('select[name=principal] option:selected').val();
            var requirements = $('textarea[name=requirements]').val();
            var priority = $('select[name=priority] option:selected').val();
            var status = $('select[name=status] option:selected').val();

            if (quote == '' || project_name == '' ||customer == '' ||
            principal == '' || requirements == '' || priority == '' ||
            priority == '' || status == '') {
                alert("对不起,表单没有填写完整");
                return;

            }

            if (customer == "请选择" || priority == "请选择" ||
                    principal == "请选择" || status == "请选择") {
                alert("对不起,表单没有填写完整");
                return;
            }

            $('#form-project-add').ajaxSubmit({
                type: 'post', // 提交方式 get/post
                url: 'add', // 需要提交的 url
                dataType: 'json',
                data: {
                    quote: $('input[name=quote]').val(),
                    budget: $('input[name=budget]').val(),
                    address: $('input[name=address]').val(),
                    houseSituation: $('textarea[name=houseSituation]').val(),
                    workforce: $('input[name=workforce]').val(),
                    area: $('input[name=area]').val(),
                    name: $('input[name=name]').val(),
                    customer_name: customer_name,
                    pm: pm,
                    designer: designer,
                    saler: saler,
                    requirements: $('textarea[name=requirements]').val(),
                    completion_time: $('input[name=completion_time]').val(),
                    houseType: houseType,
                    mealType: mealType,
                    stage: stage,
                    priority: priority,
                    status: status,
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
                beforeSend: function (xhr) {
                    layer.load(0, {shade: false});
                }
            });

        }

    </script>
@endsection
