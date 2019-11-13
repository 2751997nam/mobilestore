<div class="box no-border">
    <form role="form">
        <div class="box-body">
            <div class="form-group">
                <label>Tiêu đề *</label>
                <input type="text" ng-model="product.name" class="form-control" placeholder="Tiêu đề sản phẩm" ng-blur="prerenderSeo()"/>
            </div>
            <div class="form-group" ng-show="productVariants.length == 0">
                <div class="row">
                    <div class="col-md-6">
                        <label>Giá bán</label>
                        <div class="input-group">
                            <input type="text" ng-model="product.price" placeholder="1.000.000" awnum="price" ng-change="changePrice(product.price)" class="form-control">
                            <span class="input-group-addon">đ</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label>Giá thị trường</label>
                        <div class="input-group">
                            <input type="text" ng-model="product.high_price" placeholder="1.500.000" awnum="price" class="form-control">
                            <span class="input-group-addon">đ</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-md-6">
                        <label>Phí vận chuyển cộng thêm</label>
                        <div class="input-group">
                            <input type="text" ng-model="product.add_shipping_fee" placeholder="20.000" awnum="price" class="form-control">
                            <span class="input-group-addon">đ</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>Mô tả ngắn</label>
                {{-- <span class="pull-right">3/1000 ký tự</span> --}}
                <textarea type="text" class="form-control" ng-model="product.description" placeholder="Nhập mô tả ngắn" ng-blur="prerenderSeo()"></textarea>
            </div>
            <div class="form-group">
                <label>Nội dung</label>
                <textarea id="js-editor-content" ck-editor ng-model="product.content"></textarea>
            </div>

        </div>
    </form>
</div>
