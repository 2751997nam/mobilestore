<style type="text/css">
    .title {
        font-size: 16px;
        font-weight: 600;
        color: rgb(33, 43, 54);
    }
</style>
<div class="box no-border">
    <div class="box-body">
        <div class="box-body">
            <div class="" style="">
                <h3 class="title-info">@{{customer.full_name}}</h3>
                <p ng-show="customer.phone">SĐT: <a href="tel:@{{ customer.phone }}">@{{ customer.display_phone }}</a></p>
            </div>
            <div>
                <div class="form-group" >
                    <div style="display: flex; justify-content: space-between; align-items: center;padding-bottom: 5px;">
                        <h3 class="title" style="margin : 0;">Ghi chú</h3>
                        <span id="left-characters" ng-show="customer.note.length > 0">
                            @{{ customer.note.length }}/200 ký tự
                        </span>
                    </div>
                    <textarea
                        class="form-control" rows="3" cols="80" ng-model="customer.note" placeholder="Thêm ghi chú">
                    </textarea>
                    <button type="button" class="btn btn-flat btn-primary" ng-click="save()" style="margin-top: 5px;">Lưu ghi chú</button>
                </div>
            </div>
        </div>
    </div>
</div>
