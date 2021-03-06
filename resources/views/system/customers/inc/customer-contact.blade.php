<style type="text/css">
    .title {
        font-size: 16px;
        font-weight: 600;
        color: rgb(33, 43, 54);
    }
</style>
<div class="box no-border">
    <div class="box-body">
        <div class="form-group">
            <aside>
                <section>
                    <h3 class="title">Thông tin liên lạc</h3>
                    <p ng-show="customer.email">Email: @{{customer.email}}</p>
                    <p ng-show="customer.phone">SĐT: <a href="tel:@{{ customer.phone }}">@{{ customer.display_phone }}</a></p>
                </section>
                <hr style="border-top: 1px solid #eedcdc;">
                <section>
                    <h3 class="title">Địa chỉ</h3>
                    <p>@{{customer.address}}</p>
                    <p>
                        <span ng-show="customer.commune">@{{ customer.commune.name }}</span> 
                        <span ng-show="customer.district"> - @{{ customer.district.name }} </span> 
                        
                        <span ng-show="customer.province"> - @{{customer.province.name }}</span>
                        </p>
                </section>
            </aside>
        </div>
    </div>
</div>
