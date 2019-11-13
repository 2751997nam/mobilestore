<div class="box no-border">
    <form role="form">
        <div class="box-body">
            <h4 class="pull-left">Ảnh</h4>
            <a ngf-select="uploadImage($file)" class="pull-right" data-input="thumbnail1" style="margin-top: 10px;cursor: pointer;">Chọn ảnh</a>
            <input id="thumbnail1" class="form-control" type="text" name="filepath" style="display: none;" ng-model="post.image_url">
            <br/><br/>
            <div class="text-center">
                <img ng-if="post.image_url && post.image_url != ''" style="margin-top:15px;max-height:150px;" ng-src="@{{ post.image_url }}">
                <div ng-if="post.image_url == '' || !post.image_url">
                    @include('system.posts.inc.upload-image-svg')
                </div>
            </div>
        </div>
    </form>
</div>
