<style>
  .chosen-container-single, .chosen-container-multi {
        width: 100%!important;
    }
    .chosen-single {
            height: 34px!important;
            padding-top: 5px!important;
            font-size: 15px;
        }
</style>
<div class="modal fade" id="modal-default">
          <div class=" modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" ng-click="closeModal()" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Sửa thông tin khách hàng</h4>
              </div>
              <div class="modal-body">
                  <div class="form-group">
                    <label>Số điện thoại</label>
                    <input type="text" class="form-control" ng-model="customer.phone">
                  </div>
                  <div class="form-group">
                    <label>Họ và tên</label>
                    <input type="text" class="form-control" ng-model="customer.full_name">
                  </div>
                  <div class="form-group">
                    <label>Email</label>
                    <input type="text" class="form-control" ng-model="customer.email">
                  </div>
                  <div class="form-group">
                    <label>Tỉnh/thành phố</label>
                    <select 
                        class="form-control" 
                        id="province" 
                        ng-model="customer.province" 
                        ng-change="setLocations('district',true)" 
                        chosen
                      >
                          <option value="">-- Chọn tỉnh/thành phố --</option>
                          <option ng-repeat="item in provinces"  >@{{ item.name }}</option>
                      </select>
                  </div>
                  <div class="form-group">
                    <label>Quận/huyện</label>
                      <select 
                        class="form-control" 
                        id="district" 
                        ng-model="customer.district"
                        ng-change="setLocations('commune',true)" 
                        chosen
                      >
                          <option value="">-- Chọn quận/huyện --</option>
                          <option ng-repeat="item in districts" >@{{ item.name }}</option>
                      </select>
                  </div>
                  <div class="form-group">
                    <label>Xã/phường</label>
                      <select 
                        class="form-control" 
                        id="commune" 
                        ng-model="customer.commune"
                        chosen
                      >
                          <option value="" >-- Chọn xã/phường --</option>
                          <option ng-repeat="item in communes" >@{{item.name}}</option>
                      </select>
                  </div>
                  <div class="form-group">
                    <label>Địa chỉ</label>
                    <input type="text" class="form-control" ng-model="customer.address">
                  </div>
                  <div class="form-group">
                    <label>Ghi chú</label>
                    <span class="pull-right " id="left-characters" ng-show="customer.note.length > 0">@{{ customer.note.length }}/200 ký tự</span>
                      <textarea 
                          class="form-control" rows="3" cols="80" ng-model="customer.note" placeholder="Thêm ghi chú">
                      </textarea>
                  </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-flat btn-default pull-left" ng-click="closeModal()">Đóng</button>
                <button type="button" class="btn btn-flat btn-primary" ng-click="save()">Lưu</button>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
