<!DOCTYPE html>
<html>
@include('system.layout.head')
<body class="hold-transition skin-blue sidebar-mini fixed" ng-app="system" ng-cloak>
    <div class="wrapper"
        {{ (isset($ngController)) ? "ng-controller=$ngController" : "" }} >
        @if (!isset($header) || $header)
            @include('system.layout.header')
        @else
            @yield('header')
        @endif

        @include('system.layout.sidebar')

        <div class="content-wrapper">
            @yield('content')
        </div>
        @include('system.layout.footer')
    </div>
    @include('system.layout.script')
</body>
</html>
