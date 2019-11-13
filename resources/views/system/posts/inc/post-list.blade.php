<div style="position: relative">
    <div class="bulk-actions" ng-show="selectedPosts.length > 0">
        <div class="bulk-actions-inner">
            <ul class="bulk-actions-inner-bar">
                <li class="segments" style="background: #dfe3e8; font-weight: bold">
                    <input id="js-reset-check-all" type="checkbox" ng-click="resetOrCheckAll()" >
                    <span>@{{ selectedPosts.length }} bài viết được chọn</span>
                </li>
                <li class="segments" ng-click="deletePosts()">
                    Xóa
                </li>
            </ul>
        </div>

    </div>
</div>
<div class="table-responsive">
    <table class="table table-hover">
        <br>
        <tbody>
            <tr>
                <th style="width: 10px">
                    <input type="checkbox" id="js-post-checkall" ng-click="selectAllPosts()">
                </th>
                <th style="width: 55px"></th>
                <th>
                    <a href="javascript:void(0)" style="color: #333" ng-click="sortPost('name')">
                        Tên bài viết
                        <span class="ml-1">
                            <i
                                ng-show="sorts.field === 'name' && sorts.type !== 'asc'"
                                class="fa fa-long-arrow-down"
                                aria-hidden="true"
                            ></i>
                            <i
                                ng-show="sorts.field === 'name' && sorts.type !== 'desc'"
                                class="fa fa-long-arrow-up"
                                aria-hidden="true"
                            ></i>
                        </span>
                    </a>
                </th>

                <th>Danh mục</th>
                <th>Trạng thái</th>
                <th style="width: 150px;"></th>
            </tr>
            <tr ng-show="posts.length > 0" ng-repeat="(index, post) in posts" class="post-item" ng-click="navigate(post.editUrl)">
                <td><input type="checkbox" class="js-post-checkbox" ng-click="addSelectedPosts(post.id); $event.stopPropagation();"></td>
                <td>
                    <div class="sb-post-thumbnail" ng-if="post.image_url">
                        <img style="width: 30px; height: 30px;" ng-src="@{{ getImageCdn(post.image_url, 100, 100) }}" alt="@{{ post.name }}">
                    </div>
                </td>
                <td>
                    @{{ post.name }}
                </td>
                <td>
                    <span ng-if="post.category" ng-bind="post.category.name"></span>
                </td>
                <td>
                    <span ng-if="post.status == 'ACTIVE'" class="label label-success">Công khai</span>
                    <span ng-if="post.status != 'ACTIVE'" class="label label-danger">Ẩn</span>
                </td>
                <td class="text-right">
                    <a href="@{{ post.url }}" target="_blank" class="btn btn-flat btn-primary" role="button" alt="Xem bài viết" title="Xem bài viết" ng-click="$event.stopPropagation();">
                        <i class="fa fa-eye" aria-hidden="true"></i>
                    </a>
                    {{-- <a href="@{{ post.editUrl }}" class="btn btn-flat btn-primary" role="button" alt="Sửa bài viết" title="Sửa bài viết">
                        <i class="fa fa-pencil" aria-hidden="true"></i>
                    </a> --}}
                    <button class="btn btn-flat btn-danger" ng-click="deletePost(index); $event.stopPropagation();">
                        <i class="fa fa-trash" aria-hidden="true"></i>
                    </button>
                </td>
            </tr>
            <tr ng-show="posts.length == 0">
                <td colspan="8" class="text-center">Không có bài viết nào</td>
            </tr>
        </tbody>
    </table>
</div>
