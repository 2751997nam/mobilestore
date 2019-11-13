<div class="box no-border">
    <form role="form">
        <div class="box-body">
            <div class="form-group">
                <label>Tên bộ lọc *</label>
                <input type="text" ng-model="filtering.name" class="form-control" placeholder="Nhập tên" ng-blur="prerenderDisplayName()"/>
            </div>
            <div class="form-group">
                <label>Tên hiển thị lên web *</label>
                <input type="text" ng-model="filtering.display_name" ng-change="isChangeDisplayName = true" class="form-control" placeholder="Nhập tên hiển thị"/>
            </div>
        </div>
    </form>
</div>
