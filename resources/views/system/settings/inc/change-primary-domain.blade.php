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
<div class="modal fade" id="changePrimaryDomain" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="vertical-alignment-helper">
        <div class="modal-dialog vertical-align-center">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="domain">Thay đổi tên miền chính</label>
                        <select class="form-control" id="primaryDomain">
                            <option
                                ng-selected="domain.is_primary"
                                ng-repeat="domain in domains"
                                ng-value="domain.id">@{{ domain.domain }}</option>
                        </select>
                    </div>
                    <div class="pull-right">
                        <button type="button" class="btn btn-flat btn-default" data-dismiss="modal">Hủy</button>
                        <button ng-click="changePrimaryDomain()" id="btnChangePrimaryDomain" type="button" class="btn btn-flat btn-primary">Thay đổi</button>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
</div>