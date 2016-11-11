@extends('admin.master')

@section('content')

<div class="page-container">
    <form action="" method="post" class="form form-horizontal" id="form-user-edit"
          style="margin-top: 80px">
        <input type="hidden" name="user_id_input" value="{{$user->id}}">
        <div class="row cl" style="margin-top: -43px;">
            <label class="form-label col-sm-2"><span class="c-red"></span>昵称:</label>
            <div class="formControls col-sm-6">
                <input type="text" class="input-text" name="user_name_input" value="{{$user->name}}" readonly>
            </div>
        </div>
        <div class="row cl" style="">
            <label class="form-label col-sm-2"><span class="c-red"></span>用户名:</label>
            <div class="formControls col-sm-6">
                <input type="text" class="input-text" value="{{$user->username}}" readonly>
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-sm-2"><span class="c-red"></span>新密码:</label>
            <div class="formControls col-sm-6">
                <input id="input_password" name="password" type="password" class="input-text">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-sm-2"><span class="c-red"></span>确认新密码:</label>
            <div class="formControls col-sm-6">
                <input id="input_password_confirmation" name="password_confirmation" type="password" class="input-text">
            </div>
        </div>
        <br>
        <div id="form-errors"></div>
    </form>
    <div class="row cl">
        <div class="col-sm-8 col-sm-offset-2">
            <button class="btn btn-danger radius ml-5 mt-15"
                    onclick="resetPassword();">重置密码
            </button>
        </div>
    </div>
</div>

@endsection

@section('my-js')
<script type="text/javascript">

    //重置密码
    function resetPassword() {
        var password = $("#input_password").val();
        var password_confirmation = $("#input_password_confirmation").val();

        if (password != "" && password == password_confirmation) {
            $('#form-user-edit').ajaxSubmit({
                type: 'put', // 提交方式 post
                url: '/manager/user/{{$user->id}}', // 需要提交的 url
                dataType: 'json',
                data: {
                    id: $('input[name=account_id_input]').val(),
                    password: password,
                    password_confimation: password_confirmation,
                    _token: "{{csrf_token()}}",
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

                    layer.msg("重置成功", {icon: 1, time: 3000});

                    var index = parent.layer.getFrameIndex(window.name);

                    var t = setTimeout(function () {

                        parent.layer.close(index)

                    }, 1000);
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

        } else {
            alert("请确认密码是否输入错误");
        }

    }

</script>
@endsection