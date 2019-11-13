@extends('system.layout.main')
@section('title')
    <title>Chọn giao diện</title>
@endsection
@section('css')
    <style media="screen">
        .header {
            /*margin-bottom: 15px;*/
        }
        .card {
            box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
            transition: 0.3s;
            cursor: pointer;
            position: relative;
            margin-top: 30px;
        }

        .btn-sm{
            line-height: 1!important;
        }

        .theme-detail{
            position: absolute;
            top: 40%;
            width: 100%;
            text-align: center;
        }

        .card:hover {
            box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
        }

        .theme-title{
            padding: 8px 12px;
            font-size: 1em;
            line-height: 30px;
        }

        .card.active .theme-title{
            background-color: #000000;
            color: #fff;
        }

        .card.active .btn-option-theme{
            display: block;
        }

        .btn-option-theme{
            display: none;
        }

        .card:hover .btn-option-theme, .card:hover .btn-detail{
            display: unset;
        }

        .screenshot-theme{
            background-color: #fff;
            max-width: 100%;
        }

        .card:hover .screenshot-theme{
            opacity: 1;
        }

        .btn-detail{
            padding: 15px;
            background-color: #000000;
            width: 50%;
            color: #fff;
            border-radius: 3px;
            -webkit-box-shadow: none;
            box-shadow: none;
            border: 1px solid transparent;
            opacity: .5;
            display: none;
        }

        .col-md-4{
            outline: none;
        }

    </style>
@endsection
@section('script')
    <script>
        var apiUrl = '{{ env('SB_API_URL')}}';
    </script>

    <script type="text/javascript" src="/system/js/controllers/theme-selector-controller.js?v=<?= Config::get("sa.version") ?>"></script>

@endsection
@section('content')
    <div class="content" ng-controller="ThemeSelectorController">
        @include('system.themes.inc.theme-detail-modal')
        <div class="header">
            <div class="pull-left">
                <h3 class="">
                    Giao diện
                    <span class="label label-default">@{{ themes.length }}</span>
                </h3>
                <a href="/admin/settings">
                    <i class="fa fa-angle-left"></i>
                    Quay lại cài đặt</a>
            </div>

        <div class="clearfix"></div>
        <style>
            @media screen and (min-width: 500px) {
                .theme-item {
                    width: calc(100% / 4);
                }

                .theme-item:nth-child(4n + 1) {
                    clear: left;
                }
            }

            .theme-thumbnail {
                /* height: 350px; */
                width: 100%;
            }

        </style>
        </div>
        <div class="body">
            <div style="background-color: transparent">
                <div class="">
                    <div class="row">
                        <div class="col-md-4 theme-item"  ng-click="openDetail(theme)" ng-repeat="theme in themes">
                            <div class="card" ng-class="{'active': theme.selected}">
                                <img ng-src="@{{ theme.image_url }}" alt="" class="theme-thumbnail">
                                <div class="theme-title">
                                    <span><b>@{{ theme.name }}</b></span>
                                    <span class="pull-right btn-option-theme">
                                        <button ng-show="!theme.selected" ng-click="activate(theme);$event.stopPropagation();" class="btn btn-primary btn-sm">Kích hoạt</button>
                                        <button ng-show="theme.selected" disabled class="btn btn-primary btn-sm">Đã kích hoạt</button>
                                    </span>
                                </div>
                                <div class="theme-detail">
                                    <button class="btn-detail" style="opacity: 0.8;">Chi tiết</button>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

            </div>
        </div>
    </div>
@endsection
