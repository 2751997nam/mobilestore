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
    .chosen-single{height: 30px!important;}
    li.search-field input{height: 30px!important;}
    .chosen-container-single, .chosen-container-multi {width: 100%!important;}
</style>
<div class="box no-border seo table-responsive" style="overflow: auto; height: 382px;">
    <form role="form">
        <div class="box-body with-border">
            <div class="form-group" style="position: relative;">
                <label for="">Danh mục sản phẩm áp dụng *</label>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" ng-model="filtering.allCategory" ng-checked="filtering.allCategory" ng-change="checkerApplyCategory();"> Áp dụng cho tất cả danh mục
                    </label>
                </div>
                <div class="input-group" style="position: relative;width: 100%;">
                    <select class="form-control chosen-select"
                            chosen
                            multiple
                            skip-no-results="true"
                            ng-model="filtering.categories"
                            ng-options="category.name for category in categories track by category.id"
                            ng-change="chooseCategories();"
                    >
                    </select>
                </div>
            </div>
        </div>
    </form>
</div>
