<ul class="pagination pagination-sm no-margin pull-right" ng-show="meta.page_count > 1 && meta.page_count <= 6">
    <li ng-disabled="meta.page_id == 0" ng-click="prev()"><a href="javascript:void(0)">«</a></li>
    <li
        ng-repeat="n in range(1, meta.page_count)"
        ng-click="changePage(n)"
        ng-class="meta.page_id == n - 1 ? 'active' : ''"
    >
        <a href="javascript:void(0)">@{{ n }}</a>
    </li>
    <li ng-disabled="!meta.has_next" ng-click="next()"><a href="javascript:void(0)">»</a></li>
</ul>

<ul class="pagination pagination-sm no-margin pull-right" ng-show="meta.page_count > 6 && meta.page_id < 3">
    <li ng-disabled="meta.page_id == 0" ng-click="prev()"><a href="javascript:void(0)">«</a></li>
    <li
        ng-repeat="n in range(1, 4)"
        ng-click="changePage(n)"
        ng-class="meta.page_id == n - 1 ? 'active' : ''"
    >
        <a href="javascript:void(0)">@{{ n }}</a>
    </li>
    <li ng-disabled="true"><a href="javascript:void(0)">...</a></li>
    <li ng-click="changePage(meta.page_count)">
        <a href="javascript:void(0)">@{{ meta.page_count }}</a>
    </li>
    <li ng-disabled="!meta.has_next" ng-click="next()"><a href="javascript:void(0)">»</a></li>
</ul>

<ul class="pagination pagination-sm no-margin pull-right" ng-show="meta.page_count > 6 && meta.page_id >= 3 && meta.page_count - meta.page_id > 3">
    <li ng-disabled="meta.page_id == 0" ng-click="prev()"><a href="javascript:void(0)">«</a></li>
    <li ng-click="changePage(1)">
        <a href="javascript:void(0)">1</a>
    </li>
    <li ng-disabled="true" ng-show="meta.page_id < meta.page_count - 3"><a href="javascript:void(0)">...</a></li>
    <li
        ng-repeat="n in range(meta.page_id, meta.page_id + 2)"
        ng-click="changePage(n)"
        ng-class="meta.page_id == n - 1 ? 'active' : ''"
    >
        <a href="javascript:void(0)">@{{ n }}</a>
    </li>
    <li ng-disabled="true" ng-show="meta.page_id < meta.page_count - 3"><a href="javascript:void(0)">...</a></li>
    <li ng-click="changePage(meta.page_count)">
        <a href="javascript:void(0)">@{{ meta.page_count }}</a>
    </li>
    <li ng-disabled="!meta.has_next" ng-click="next()"><a href="javascript:void(0)">»</a></li>
</ul>

<ul class="pagination pagination-sm no-margin pull-right" ng-show="meta.page_count > 6 && meta.page_count - meta.page_id <= 3">
    <li ng-disabled="meta.page_id == 0" ng-click="prev()"><a href="javascript:void(0)">«</a></li>
    <li ng-click="changePage(1)">
        <a href="javascript:void(0)">1</a>
    </li>
    <li ng-disabled="true"><a href="javascript:void(0)">...</a></li>
    <li
        ng-repeat="n in range(meta.page_count - 2, meta.page_count)"
        ng-click="changePage(n)"
        ng-class="meta.page_id == n - 1 ? 'active' : ''"
    >
        <a href="javascript:void(0)">@{{ n }}</a>
    </li>
    <li ng-disabled="!meta.has_next" ng-click="next()"><a href="javascript:void(0)">»</a></li>
</ul>
<span ng-if="meta.page_count > 0" class="pull-right mr-3 mt-2">
    Hiển thị @{{ (meta.page_id < meta.page_count - 1) ? (meta.page_id + 1) * meta.page_size : meta.total_count }} trên tổng số @{{ meta.total_count }}
</span>
