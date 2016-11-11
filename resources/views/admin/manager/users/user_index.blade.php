@extends('admin.manager.master')

@section('content')

    <div class="page-container">
        <div class="cl pd-5 bg-1 bk-gray">
            <button class="btn btn-success radius" onclick="addUser();"><i class="Hui-iconfont">&#xe600;</i>添加成员
            </button>
        </div>

        <div class="panel panel-default" style="margin-top: 6px">
            <div class="panel-header">管理员</div>
            <div class="panel-body">
                <table class="table table-border table-bg">
                    <thead>
                    <tr>
                        <th width="33%">昵称</th>
                        <th width="33%">用户名</th>
                        <th>操作</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($admins as $admin)
                        <tr style="margin-top:20px">
                            <td>{{$admin->name}}</td>
                            <td>{{$admin->username}}</td>
                            <td>
                                    <button class="btn btn-primary btn-xs radius"
                                    onclick="edit_password('/manager/user/{{$admin->id}}/edit/')">重置密码</button>
                                <form action="/manager/user/{{$admin->id}}"
                                      style="display:inline-block" method="POST"
                                    onsubmit="return delete_confirm();">
                                    {{ csrf_field() }}
                                    {{ method_field('DELETE') }}
                                    <button class="btn btn-danger btn-xs radius">删除帐号</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-header">普通成员</div>
            <div class="panel-body">
                <table class="table table-border table-bg">
                    <thead>
                    <tr>
                        <th width="33%">昵称</th>
                        <th width="33%">用户名</th>
                        <th>操作</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($commonUsers as $commonUser)
                        <tr class="">
                            <td>{{$commonUser->name}}</td>
                            <td>{{$commonUser->username}}</td>
                            <td>
                                    <button class="btn btn-primary btn-xs radius"
                                    onclick="edit_password('/commonUser/user/{{$commonUser->id}}/edit')">重置密码</button>
                                <form action="/commonUser/user/{{$commonUser->id}}"
                                    style="display:inline-block" method="POST"
                                onsubmit="return delete_confirm();">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button class="btn btn-danger btn-xs radius">删除帐号</button>
                                </form>
                            </td>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('my-js')
    <script type="text/javascript">

        //转到修改密码界面
        function edit_password(url) {
            var index = layer.open({
                type: 2,
                title: "重置密码",
                content: url,
                area: ['100%', '100%']
            });
        }

        //转到添加帐号界面
        function addUser() {
            var index = layer.open({
                type: 2,
                title: "添加成员",
                content: "user/create",
                area: ['100%', '100%']
            });

        }

        function delete_confirm() {
            if (!window.confirm('确认删除该账号？')) {
                return false;
            }
            return true;
        }
    </script>
@endsection
