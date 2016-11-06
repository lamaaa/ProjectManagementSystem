@extends('master')

@section('content')
    <style>

        .btn-primary {
            background-color: #00B83F;
            line-height: 26px;
            font-size: 16px;
            height: 40px;
            width: 95px;
        }

        .btn-default {
            line-height: 26px;
            font-size: 16px;
            height: 40px;
            width: 95px;
        }

        .btn-success {
            background-color: #00B83F;
        }

        a.btn, a.btn.size-M, span.btn, span.btn.size-M {
            line-height: 26px;
        }

        input:-webkit-autofill, textarea:-webkit-autofill, select:-webkit-autofill {
            background-color: white;
            background-image: none;
            color: rgb(0, 0, 0);
        }


    </style>
    <link href="../css/H-ui.login.css" rel="stylesheet" type="text/css"/>
    <div class=""></div>
    <div class="loginWraper" style="background: url('');">
        <div id="loginform" class="loginBox" style="background: url();">
            <form class="form form-horizontal" action="/auth/login" method="post">
                {{ csrf_field() }}
                <div class="row cl" style="margin-top: -100px;">
                    <div class="formControls" style="text-align: center">
                        <h2>你好, 欢迎!</h2>
                    </div>
                </div>
                <div class="row cl">
                    <label class="form-label col-3"><i class="Hui-iconfont">&#xe60d;</i></label>
                    <div class="formControls col-8">
                        <input id="" name="username" type="text" placeholder="帐号" class="input-text size-L">
                    </div>
                </div>


                <div class="row cl">
                    <label class="form-label col-3"><i class="Hui-iconfont">&#xe60e;</i></label>
                    <div class="formControls col-8">
                        <input id="input_password" name="password" type="password" placeholder="密码" class="input-text size-L">
                    </div>
                </div>
                <div class="row">
                    <div class="formControls" style="text-align: center">
                        <button type="submit" class="btn btn-success radius size-L">&nbsp;登&nbsp;&nbsp;&nbsp;&nbsp;录&nbsp;</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="footer" style="background-color: black">Copyright <a href="https://github.com/lamaaa">@Lam</a></div>
@endsection

