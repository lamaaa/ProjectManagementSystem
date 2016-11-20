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
                        <input type="checkbox" name="customerManagers" value="{{$user->id}}">
                            {{$user->name}}&nbsp&nbsp&nbsp
                        @endif
                    @endforeach
                </div>
                <div class="Validform_checktip"></div>
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
            var name = $('input[name=name]').val();
            var company = $('input[name=company]').val();
            var phone = $('input[name=phone]').val();
            var description = $('input[name=description]').val();

            // 获取项目经理
            var customerManagers = new Array();
            $('input[name=customerManagers]:checked').each(function(){
                customerManagers.push(this.value);
            });

            if (name == "" || company == "" || phone == "" ||
                      customerManagers.length == 0
            )
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
                    customerManagers: customerManagers,
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