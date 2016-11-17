@extends('admin.master')

@section('content')
    <header class="Hui-header cl"><a class="Hui-logo l" title="身份" href="">您好!</a><span
                class="Hui-subtitle l">{{ $name }}</span>
        <ul class="Hui-userbar">
            <li></li>
            <li><a href="/logout" style="color:white;">退出</a></li>
        </ul>
        <a href="javascript:;" class="Hui-nav-toggle Hui-iconfont" aria-hidden="false">&#xe667;</a>
    </header>
    <aside class="Hui-aside">
        <input runat="server" id="divScrollValue" type="hidden" value=""/>
        <div class="menu_dropdown bk_2">
            {{--拥有“admin”和“customerManager”角色才能看到客户管理--}}
            @role(['admin','customerManager_*'])
            <dl id="menu-product">
                <dt><i class="Hui-iconfont">&#xe637;</i> 客户管理<i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i>
                </dt>
                <dd>
                    <ul>
                        <li><a _href="/manager/customer?reset=1" data-title="客户" href="javascript:void(0)">客户列表页</a></li>
                        <li><a _href="statistics_crm" data-title="统计信息" href="javascript:void(0)">统计信息</a></li>
                    </ul>
                </dd>
            </dl>
            @endrole
            <dl id="menu-order">
                <dt><i class="Hui-iconfont">&#xe687;</i> 项目管理<i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i>
                </dt>
                <dd>
                    <ul>
                        <li><a _href="project_list" data-title="项目列表" href="javascript:void(0)">项目列表页</a></li>
                        <li><a _href="statistics_project" data-title="统计信息" href="javascript:void(0)">统计信息</a>
                        </li>
                    </ul>
                </dd>
            </dl>
            <dl id="menu-order">
                <dt><i class="Hui-iconfont">&#xe687;</i> 帐号管理<i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i>
                </dt>
                <dd>
                    <ul>
                        {{--不同的角色进入不同的帐号管理--}}
                        @role('admin')
                        <li><a _href="/manager/user" data-title="帐号管理" href="javascript:void(0)">管理成员帐号</a></li>
                        @endrole
                        @role('commonUser')
                        <li><a _href="/commonUser/user" data-title="帐号管理" href="javascript:void(0)">管理成员帐号</a></li>
                        @endrole
                    </ul>
                </dd>
            </dl>
        </div>
    </aside>
    <div class="dislpayArrow"><a class="pngfix" href="javascript:void(0);" onClick="displaynavbar(this)"></a></div>
    <section class="Hui-article-box">
        <div id="Hui-tabNav" class="Hui-tabNav">
            <div class="Hui-tabNav-wp">
                <ul id="min_title_list" class="acrossTab cl">
                    <li class="active"><span title="我的桌面" data-href="/">我的桌面</span><em></em></li>
                </ul>
            </div>
            <div class="Hui-tabNav-more btn-group"><a id="js-tabNav-prev" class="btn radius btn-default size-S"
                                                      href="javascript:void(0);"><i class="Hui-iconfont">&#xe6d4;</i></a><a
                        id="js-tabNav-next" class="btn radius btn-default size-S" href="javascript:;"><i
                            class="Hui-iconfont">&#xe6d7;</i></a></div>
        </div>
        <div id="iframe_box" class="Hui-article">
            <div class="show_iframe">
                <div style="display:none" class="loading"></div>
                <iframe scrolling="yes" frameborder="0" src="/welcome"></iframe>
            </div>
        </div>
    </section>
@endsection