@extends('admin.master')

@section('content')
    <div class="page-container">

        <form action="" method="post" class="form form-horizontal"
              id="form-order-edit" style="margin-top: 80px">
            <div class="row cl" style="    margin-top: -43px;">
                <label class="form-label col-sm-2"><span class="c-red"></span>用户名:</label>
                <div class="formControls col-sm-6">
                    <input type="text" class="input-text" id="input_username" name="username">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-sm-2"><span class="c-red"></span>昵称:</label>
                <div class="formControls col-sm-6">
                    <input type="text" class="input-text" id="input_name" name="name">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-sm-2"><span class="c-red"></span>邮箱:</label>
                <div class="formControls col-sm-6">
                    <input id="input_email" type="text" class="input-text" name="email">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-sm-2"><span class="c-red"></span>角色:</label>
                <div class="formControls col-sm-6 ">
                    <span class="select-box">
                        <select name="role" class="select" id="select_role">
                            <option value="admin">管理员</option>
                            <option value="commonUser">普通用户</option>
                        </select>
                    </span>
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-sm-2"><span class="c-red"></span>密码:</label>
                <div class="formControls col-sm-6">
                    <input id="input_password" type="password" class="input-text" name="password">
                </div>
            </div>

            <div class="row cl">
                <label class="form-label col-sm-2"><span class="c-red"></span>确认密码:</label>
                <div class="formControls col-sm-6">
                    <input id="input_password_confirm" type="password" class="input-text" name="password_confirmation">
                </div>
            </div>
            <br>
            <div id="form-errors"></div>
        </form>
        <div class="row cl">
            <div class="col-sm-8 col-sm-offset-2">
                <button class="btn btn-secondary radius mt-15 ml-5"
                        onclick="addUserConfirm();">确认</button>
            </div>
        </div>
    </div>
@endsection
@section('my-js')
    <script type="text/javascript">

        // 输入确认
        function addUserConfirm() {
            var username = $("#input_username").val();
            var name = $("#input_name").val();
            var role = $('#select_role option:selected').val();
            var password = $("#input_password").val();
            var password_confirmation = $("#input_password_confirm").val();
            var email = $("#input_email").val();

            if (username == '' || role == '' || password == '' ||
                    password_confirmation == '' || email == '' || name == '') {
                alert('输入信息不完整！');
                return;
            }

            if (!(/^[\w-]+(\.[\w-]+)*@([\w-]+\.)+[a-zA-Z]+$/.test(email))){
                alert('请输入有效邮箱');
                return;
            }

            if (password != password_confirmation) {
                alert('两次密码不一致！');
                return;
            }
            else
            {
                $.ajax({
                    type: 'POST',
                    url: '/manager/user',
                    dataType: 'json',
                    data:{
                        username: username,
                        name: name,
                        email: email,
                        role: role,
                        password: password,
                        password_confirmation: password_confirmation,
                        _token: "{{csrf_token()}}"
                    },
                    success: function(data)
                    {
                        if (data == null)
                        {
                            layer.msg('服务端错误', {icon: 2, time: 2000})
                        }
                        if (data.status != 0)
                        {
                            layer.msg(data.message, {icon: 2, time: 2000})
                        }

                        layer.msg(data.message, {icon: 1, time: 2000});
                        parent.location.reload();
                    },
                    error: function(data) {
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
        }

    </script>
@endsection
