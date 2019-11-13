<div class="modal fade" id="modalThemeDetail">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <img class="screenshot-theme" ng-src="@{{ themeDetail.image_url }}" alt="">
                    </div>
                    <div class="col-md-6">
                        <h3 class="">@{{ themeDetail.name }}</h3>
                        <p>@{{ themeDetail.description }}</p>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Đóng</button>
                <button ng-show="!themeDetail.selected" id="btnSelectInDetail" ng-click="activate(themeDetail);" type="button" class="btn btn-primary">Kích hoạt</button>
                <button ng-show="themeDetail.selected" disabled class="btn btn-primary">Đã kích hoạt</button>
            </div>
        </div>
    </div>
</div>
