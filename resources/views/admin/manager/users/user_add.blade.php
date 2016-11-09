@extends('admin.master')

@section('content')
    <div class="page-container">

        <form action="/manager/user" method="post" class="form form-horizontal"
              id="form-order-edit" style="margin-top: 80px"
                onsubmit="return addUserConfirm();">
            {{csrf_field()}}
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

            <div class="row cl">
                <div class="col-sm-8 col-sm-offset-2">
                    <button class="btn btn-secondary radius mt-15 ml-5">确认</button>
                </div>
            </div>
            <br>
            @include('errors.list')
        </form>
    </div>
@endsection
@section('my-js')
    <script type="text/javascript">

        // 输入确认
        function addUserConfirm() {
            var username = $("#input_username").val();
            var role = $('#select_role option:selected').val();
            var password = $("#input_password").val();
            var password_confirm = $("#input_password_confirm").val();
            var email = $("#input_email").val();

            if (username == '' || role == '' || password == '' ||
                    password_confirm == '' || email == '') {
                alert('输入信息不完整！');
                return false;
            }

            if (!(/^[\w-]+(\.[\w-]+)*@([\w-]+\.)+[a-zA-Z]+$/.test(email))){
                alert('请输入有效邮箱');
                return false;
            }

            if (password != password_confirm) {
                alert('两次密码不一致！');
                return false;
            }
            else {
                return true;
            }
        }

    </script>
@endsection
