<style>
    .vertical-alignment-helper {
        display:table;
        height: 100%;
        width: 100%;
        pointer-events:none;
    }
    .vertical-align-center {
        /* To center vertically */
        display: table-cell;
        vertical-align: middle;
        pointer-events:none;
    }
    .modal-content {
        /* Bootstrap sets the size of the modal in the modal-dialog class, we need to inherit it */
        width:inherit;
    max-width:inherit; /* For Bootstrap 4 - to avoid the modal window stretching full width */
        height:inherit;
        /* To center horizontally */
        margin: 0 auto;
        pointer-events:all;
    }
</style>

<!-- Modal -->
<div class="modal fade" id="addDomain" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="vertical-alignment-helper">
        <div class="modal-dialog vertical-align-center">
            <div class="modal-content">
                {{-- <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                    </button>
                     <h4 class="modal-title" id="myModalLabel">Thêm tên miền</h4>

                </div> --}}
                <div class="modal-body">
                    <div class="form-group">
                        <label for="domain">Tên miền</label>
                        <input ng-model="domain" ng-change="formatDomain()" max="254" id="domain" class="form-control" placeholder="ví dụ: shopthoitrang.com" type="text">
                        <p style="padding-top:5px;" class="text-muted">Nhập vào tên miền bạn muốn liên kết</p>
                    </div>
                    <div class="pull-right">
                        <button type="button" class="btn btn-flat btn-default" data-dismiss="modal">Hủy</button>
                        <button ng-click="save()" ng-show="checkDomain(domain)" id="btnSave" type="button" class="btn btn-flat btn-primary">Thêm</button>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
</div>