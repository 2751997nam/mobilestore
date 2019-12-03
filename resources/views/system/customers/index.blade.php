@extends('system.layout.main')
@section('title')
<title>Danh sách khách hàng</title>
@endsection
@section('css')
<style media="screen">
    .header {
        margin-bottom: 25px;
    }

    .import-export a {
        color: #637381;
        padding: 7px 10px;
        margin-left: -7px;
        font-size: 15px;
        font-weight: 300;
        margin-right: 15px;
    }

    .import-export a:hover {
        background-color: #ccc;
        border-radius: 3px;
    }

    button.add {
        margin-top: 30px;
    }
    .product-item {
        cursor: pointer;

    }

    .product-item .view-product{
        display: none;
    }
    .product-item:hover .view-product{
        display: block;
    }
    .box-body {
        overflow: unset;
    }
</style>
@endsection
@section('script')
<script>
    $('#filter > button').on('click', function(event) {
        $(this).parent().toggleClass('open');
    });
    $('body').on('click', function(e) {
        if (!$('#filter').is(e.target) &&
            $('#filter').has(e.target).length === 0 &&
            $('.open').has(e.target).length === 0
        ) {
            $('#filter').removeClass('open');
        }
    });
</script>
<script>
    var apiUrl = '{{ env('SB_API_URL')}}';
</script>
<script src="" charset="utf-8"></script>
<script src="/system/js/controllers/pagination/pagination-controller.js" charset="utf-8"></script>
<script type="text/javascript" src="/system/js/controllers/customer/customer-list-controller.js?v=<?= Config::get("sa.version") ?>"></script>
@endsection
@section('content')
<div class="content" ng-controller="CustomerListController" id="CustomerListController">
    <div class="header">
        <div class="pull-left">
            <h3 class="">Danh sách khách hàng</h3>
        </div>
        <div class="clearfix">
        </div>
    </div>
    <div class="body">
        <div class="box no-border">
            {{-- <div class="box-header with-border"> --}}
                <div class="nav-tabs-custom">
                <ul class="nav nav-tabs hide-xs" style="border-bottom: 0px;">
                  <li class="active"><a href="#tab_1" data-toggle="tab">Toàn bộ khách hàng</a></li>
                </ul>

            <!-- /.tab-content -->
          </div>
            {{-- </div> --}}
            <!-- /.box-header -->
            <div class="box-body row" style="margin-bottom: 10px;">
                <div class="col-md-8" style="margin-bottom: 5px">
                    <div class="input-group">

                        <!-- /btn-group -->
                        <input type="text" class="form-control" placeholder="Họ và tên, số điện thoại, email, địa chỉ, v.v." ng-model="searchFilter"  ng-keydown="$event.keyCode === 13 && search()">
                        <div class="input-group-btn">
                            <button type="button" class="btn btn-default btn-flat" ng-click="search()">
                                <i class="fa fa-search"></i>
                                Tìm kiếm
                            </button>

                        </div>
                    </div>
                </div>
                <div class="col-md-4 hide-xs" style="">

                        <select name="" class="form-control" ng-model="filter" ng-change="searchByFilter()">
                            <option value="Khách hàng mới nhất" selected="selected" >Khách hàng mới nhất</option>
                            <option value="Cập nhật gần đây">Cập nhật gần đây</option>
                            <option value="Nhiều đơn nhất">Nhiều đơn nhất</option>
                            <option value="Chi nhiều tiền nhất">Chi nhiều tiền nhất</option>
                            <option value="Theo tên (A-Z)">Theo tên (A-Z)</option>
                        </select>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <br>
                    <tbody>
                        <tr>
                            <th >Tên khách hàng</th>
                            <th >Số điện thoại</th>
                            <th >Email</th>
                            <th >Địa chỉ</th>
                        </tr >
                        <tr class="product-item" ng-repeat="(index,item) in customers" ng-click="navigate(getEditUrl(item))">
                            <td>@{{ item.full_name }}</td>
                            <td><a href="tel:@{{ item.phone }}">@{{ item.display_phone }}</a></td>
                            <td><a href="https://mail.google.com/mail/u/0/?view=cm&tf=1&fs=1&to=@{{ item.email }}">@{{ item.email }}</a></td>
                            <td>@{{ item.address }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix">
                @include('system.pagination')
            </div>
        </div>
    </div>
</div>
@endsection
