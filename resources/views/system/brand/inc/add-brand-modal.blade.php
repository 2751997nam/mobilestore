@php
    $width = 100;
@endphp
<div class="modal fade" id="modalAddBrand">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">
                    <span ng-show="mode == 'create'">Thêm thương hiệu mới</span>
                    <span ng-show="mode == 'update'">Chỉnh sửa thương hiệu</span>
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
                                <input id="thumbnail" class="form-control" type="text" name="filepath" ng-model="currentBrand.image_url">
                            </div>
                            <img id="holder" style="margin-top:15px;max-height:100px;" ng-src="@{{ currentBrand.image_url }}">
                        </div>
                        <div class="form-group">
                            <label>Tên thương hiệu *</label>
                            <input id="brandName" type="text" class="form-control" ng-model="currentBrand.name">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Slug</label>
                            <input type="text" class="form-control" ng-model="currentBrand.slug">
                        </div>
                        <div class="form-group">
                            <label>Độ ưu tiên</label>
                            <input type="number" min="0" autocomplete="false" id="number" name="" value="" class="form-control" ng-model="currentBrand.sorder">
                        </div>
                        <div class="form-group">
                            <label>Mô tả</label>
                            <textarea name="" ng-keyup="$event.stopPropagation();" value="" class="form-control" ng-model="currentBrand.description"></textarea>
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
