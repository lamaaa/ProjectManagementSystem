@extends('master')

@section('content')
    <div class="pd-20">
        <div class="cl pd-5 bg-1 bk-gray">
  		<span class="l">
  			<a href="javascript:;" onclick="project_add('添加项目','project_add')" class="btn btn-success radius"><i
                        class="Hui-iconfont">&#xe600;</i> 添加项目</a>

            	<a href="javascript:;" onclick="export_excel_all_projects();"
                   class="btn btn-primary radius">导出列表(Excel)</a>
  		</span>
            {{--            <span class="r">共有数据：<strong>{{count($products)}}</strong> 条</span>--}}
            <div id="sort_mark" hidden>{{$sort}}</div>

        </div>
        <div class="mt-20">
            <table class="table table-border table-bordered table-hover table-bg table-sort">
                <thead>
                <tr class="text-c">

                    <th width="40">项目ID</th>
                    <th width="">项目名称</th>
                    <th width="">优先级</th>
                    <th width="">现报价</th>
                    <th width="">项目进度</th>
                    <th width="">客户</th>
                    <th width="">项目经理</th>
                    <th width="">预期完成时间</th>
                    <th width="">操作</th>
                    {{--<th width="" onclick="">操作</th>--}}
                </tr>
                </thead>
                <tbody>
                @if(count($projects))
                @foreach($projects as $project)
                    <tr class="text-c">
                        <td>{{$project->id}}</td>
                        <td>
                            <a title="测试" href="javascript:;"
                               onclick="customer_content('查看详情','project_detail?id={{$project->id}}')" class="ml-5"
                               style="text-decoration:none;color: #0a6999;">{{$project->name}}</a>
                        </td>
                        <td>
                            @if(value($project->priority) === 1)
                                中
                            @elseif(value($project->priority) === 2)
                                高
                            @elseif(value($project->priority) === 0)
                                低
                            @endif
                        </td>
                        <td>{{$project->quote}}</td>
                        <td>{{$project->stage}}</td>
                        <td>
                            <div class="label label-primary radius">{{$project->customer_name}}</div>
                        </td>
                        <td>
                            <div class="label label-secondary radius">{{$project->saler_name}}</div>
                        </td>
                        <td>
                            <div class="label label-success radius">{{$project->pm_name}}</div>
                        </td>
                        <td>{{$project->completion_time}}</td>
                        <a href="javascript:;" onclick="delete_customer('/manager/customer_delete/?id={{$customer->id}}')"
                           class="btn btn-danger radius">删除</a>
                    </tr>
                @endforeach
                @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('my-js')
    <script type="text/javascript">
        //动态指定 tag 的宽度
        //        var tag_content = $(".tag").innerHTML;
        //        var length = tag_content.length;
        //        $(".tag").style.width = 10 * length;

        $(".tag").each(function () {
            if (this.innerHTML == "") {
                this.style.backgroundColor = "white";
                this.style.height = "0px";
                this.style.width = "0px";
            } else {
                var length = this.innerHTML.length;
                this.style.width = length * 12 + "px";
            }
        });

        function project_add(title, url) {
            var index = layer.open({
                type: 2,
                title: title,
                content: url,
                area: ['100%', '100%']
            });
        }

        function customer_content(title, url) {
            var index = layer.open({
                type: 2,
                title: title,
                content: url,
                area: ['100%', '100%']
            });
        }

        //导出项目表格
        function export_excel_all_projects() {
            location.href = "export_excel_all_projects";
        }
    </script>
@endsection