<style media="screen">
    ul.popup {
        position : absolute; background-color: white; border: 1px solid #ccc; width : 100%;
        z-index: 5;
        margin : 0;
        padding: 0;
    }
    ul.popup  li {
        margin: 0;
        padding: 8px;
        list-style-type: none;
        cursor: pointer;

    }
    ul.popup li:hover {
        background-color: #ddd;
    }
</style>
<style>
    .chosen-single{height: 30px!important;}
    li.search-field input{height: 30px!important;}
</style>
<div class="box no-border seo">
    <form role="form">
        <div class="box-body with-border">
            <div class="form-group" style="position: relative;">
                <label for="">Trạng thái</label>
                <div class="input-group" style="width: 100%;">
                    <select class="form-control"
                        ng-model="product.status"
                        ng-options="status.value as status.name for status in statuses">
                    </select>
                </div>
            </div>
        </div>
        <div class="box-body with-border">
            <div class="form-group" style="position: relative;">
                <label for="">Danh mục</label>
                <select class="form-control chosen-select"
                        multiple
                        chosen
                        {{-- persistent-create-option="true" --}}
                        {{-- skip-no-results="true" --}}
                        ng-model="product.categories"
                        ng-change="chooseCategories()"
                        ng-options="category.name for category in categories track by category.id">
                </select>
            </div>
        </div>
        <div class="box-body with-border">
            <div class="form-group" style="position: relative;">
                <label for="">Thương hiệu</label>
                <select class="form-control"
                        chosen
                        create-option-text="'Tạo thêm thương hiệu'"
                        persistent-create-option="true"
                        skip-no-results="true"
                        create-option="createBrand"
                        ng-model="product.brand"
                        ng-options="brand.name for brand in brands track by brand.id">
                </select>
            </div>
        </div>
    </form>
</div>
