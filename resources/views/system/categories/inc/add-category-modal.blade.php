@php
    $width = 100;
@endphp
<div class="modal fade" id="modalAddCategory">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">
                    <span ng-show="mode == 'create'">Thêm danh mục mới</span>
                    <span ng-show="mode == 'update'">Chỉnh sửa danh mục</span>
                </h4>
            </div>
            <div class="modal-body" ng-keyup="$event.keyCode == 13 && save()">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Hình ảnh</label>
                            <div class="input-group">
                               <span class="input-group-btn">
                                 <a ngf-select="uploadImage($file)" data-input="thumbnail" data-preview="holder" class="btn btn-primary">
                                   <i class="fa fa-picture-o"></i> Chọn ảnh
                                 </a>
                               </span>
                                <input id="thumbnail" class="form-control" type="text" name="filepath" ng-model="currentCategory.image_url">
                            </div>
                            <img id="holder" style="margin-top:15px;max-height:100px;" ng-src="@{{ currentCategory.image_url }}">
                        </div>
                        <div class="form-group">
                            <label>Tên danh mục *</label>
                            <input id="categoryName" type="text" class="form-control" ng-model="currentCategory.name">
                        </div>
                        <div class="form-group">
                            <label>Mô tả</label>
                            <textarea name="" ng-keyup="$event.stopPropagation();" value="" class="form-control" ng-model="currentCategory.description"></textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Slug</label>
                            <input type="text" class="form-control" ng-model="currentCategory.slug">
                        </div>
                        <div class="form-group">
                            <label>Độ ưu tiên</label>
                            <input type="number" min="0" autocomplete="false" id="number" name="" value="" class="form-control" ng-model="currentCategory.sorder">
                        </div>
                        <div class="form-group">
                            <label>Trạng thái</label>
                            <select class="form-control" name="" ng-init="currentCategory.is_hidden = 0" ng-model="currentCategory.is_hidden">
                                <option ng-selected="currentCategory.is_hidden == status.id"
                                        ng-repeat="status in [{id: 0, text: 'Hiển thị'}, {id: 1, text: 'Ẩn'}]"
                                        ng-value="status.id">@{{ status.text }}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="checkbox" ng-model="currentCategory.is_display_home_page" ng-checked="currentCategory.is_display_home_page">
                            <label>Hiển thị trên trang chủ</label>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Đóng</button>
                <button id="btnUpdate" ng-show="mode == 'create'" ng-click="save()" type="button" class="btn btn-primary">Thêm</button>
                <button id="btnSave" ng-show="mode == 'update'" ng-click="save()" type="button" class="btn btn-success">Lưu lại</button>
            </div>
        </div>
    </div>
</div>
