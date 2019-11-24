<!DOCTYPE html>
<html lang="en">
    <head>
        <title>@yield('title')</title>
        @include('frontend.layout.head')
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        @yield('css')
    </head>

    <body>

        <div class="super_container">
            
            @include('frontend.layout.header')

            @yield('content')

            @include('frontend.common.recent-viewed')

            @include('frontend.common.new-letter')

            @include('frontend.layout.footer')
        </div>

        @include('frontend.layout.js')
        @yield('js')
    </body>
</html>