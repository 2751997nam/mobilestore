<div class="box no-border" ng-show="productVariants.length == 0">
    <form role="form">
        <div class="box-body">
            <h4 class="">Quản lý kho</h4>
            <div class="form-group">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Mã SKU</label>
                            <input type="text" ng-model="product.sku" placeholder="Mã SKU" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        {{--                    <div class="form-group">--}}
                        {{--                        <label>Tồn kho</label>--}}
                        {{--                        <div class="input-group">--}}
                        {{--                            <input type="text" ng-model="product.inventory" awnum="price" class="form-control">--}}
                        {{--                        </div>--}}
                        {{--                    </div>--}}
                    </div>
                </div>
            </div>
            <br>
    </form>
</div>
</div>
