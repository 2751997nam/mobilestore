<div class="modal fade" id="modalDeleteCategory">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Xác nhận xóa thương hiệu</h4>
            </div>
            <div class="modal-body">
                <p>Sau khi xóa sẽ không khôi phục lại được! Bạn chắc chắn xóa?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Đóng</button>
                <button id="doDelete" ng-click="doDelete()" type="button" class="btn btn-danger">Xóa</button>
            </div>
        </div>
    </div>
</div>
