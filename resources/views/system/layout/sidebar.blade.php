<?php
    function check_is_active($url) {
        return $_SERVER['REQUEST_URI'] == $url ? "active" : "";
    }
    function check_is_active_tree($item) {
        foreach ($item['children'] as $key => $value) {
            if (check_is_active($value['url'])) {
                return "active";
            }
        }
        return "";
    }
?>
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="/system/images/no-avatar.png" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>{{ Auth::user() ? Auth::user()->name : "Quản trị viên" }}</p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        <ul class="sidebar-menu" data-widget="tree">
            @foreach (config('menu') as $item)
                @if (isset($item['url']))
                    @if (isset($item['children']))
                        <li class="treeview {{ check_is_active_tree($item) }}">
                            <a href="{{ $item['url'] }}">
                                {!! $item['icon'] !!} <span>{{ $item['title'] }}</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                @foreach ($item['children'] as $child)
                                    <li class="{{ check_is_active($child['url']) }}"><a href="{{ $child['url'] }}"><i class="fa fa-circle-o"></i> {{ $child['title'] }}</a></li>
                                @endforeach
                            </ul>
                        </li>
                    @else
                        <li class="{{ check_is_active($item['url']) }}">
                            <a href="{{ $item['url'] }}">
                                {!! $item['icon'] !!} <span>{{ $item['title'] }}</span>
                            </a>
                        </li>
                    @endif
                @else
                    <li class="header">{{ $item['title'] }}</li>
                @endif

            @endforeach
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>
