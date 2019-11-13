<div class="input-group">
    <!-- /btn-group -->
    <input ng-model="keyword" type="text" class="form-control" ng-keyUp="$event.keyCode === 13 && searchFilter()"
        placeholder="Tìm kiếm bộ lọc">
    <div class="input-group-btn">
        <button type="button" class="btn btn-default btn-flat" ng-click="searchFilter()">
            <i class="fa fa-search"></i>
            Tìm kiếm
        </button>
    </div>
</div>
