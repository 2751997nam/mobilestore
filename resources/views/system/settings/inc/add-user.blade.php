<div class="modal fade" id="modalAddUser">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">
                    <span>Thêm quản trị viên</span>
                </h4>
            </div>
            <div class="modal-body" ng-keyup="$event.keyCode == 13 && save()">
                <div class="form-group">
                    <label for="">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" ng-model="user.email">
                    <span class="text-danger" ng-if="validationMessages.email">@{{ validationMessages.email }}</span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Đóng</button>
                <button ng-click="save()" type="button" class="btn btn-primary">Thêm</button>
            </div>
        </div>
    </div>
</div>
