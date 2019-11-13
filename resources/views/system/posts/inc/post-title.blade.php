<div class="box no-border">
    <form role="form">
        <div class="box-body">
            <div class="form-group">
                <label>Tiêu đề *</label>
                <input type="text" ng-model="post.name" class="form-control" placeholder="Tiêu đề bài viết" ng-blur="prerenderSeo()"/>
            </div>

            <div class="form-group">
                <label>Mô tả ngắn</label>
                <textarea type="text" class="form-control" ng-model="post.description" placeholder="Nhập mô tả ngắn" ng-blur="prerenderSeo()"></textarea>
            </div>
            <div class="form-group">
                <label>Nội dung</label>
                <textarea ck-editor ng-model="post.content"></textarea>
            </div>

        </div>
    </form>
</div>
