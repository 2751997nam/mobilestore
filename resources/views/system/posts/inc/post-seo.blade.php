<style media="screen">
    .seo {

    }
    .seo .preview {
        font-family: Arial, "Helvetica Neue", Helvetica, sans-serif
    }
    .seo .preview h3{
        font-size: 18px;
        line-height: 1.33;
        font-weight: normal;
        margin: 0;
        padding: 0;
        color : #1a0dab;
    }
    .seo .preview a{
        font-size: 14px;;
        padding-top: 1px;
        line-height: 1.43;
        color : #006621;

    }
    .seo .preview p{
        font-size: 14px;;
        padding-top: 1px;
        line-height: 1.54;
        color : #545454;
        word-wrap: break-word;

    }
    .seo span.pull-right {
        color : #637381;
    }
</style>
<div class="box no-border seo collapsed-box" >
    <div class="box-header">
        <h3 class="box-title pull-left">Tối ưu SEO</h4>
        <div class="box-tools pull-right">
            <a href="javascript:;" class="pull-right" data-widget="collapse" data-toggle="tooltip" style="margin-top: 10px;">
                Sửa đổi
            </a>
        </div>
        <div class="clearfix"></div>
        <br>
        <div class="preview">
            <h3>
                <span ng-show="post.meta_title != ''">@{{ post.meta_title }}</span>
                <span ng-show="post.meta_title == ''">@{{ post.name }}</span>
            </h3>
            <a href="javascript:;">@{{ appUrl }}/@{{post.slug}}</a>
            <p>
                <span ng-show="post.meta_description != ''">
                    @{{post.meta_description}}
                </span>
                <span ng-show="post.meta_description == ''">
                     @{{post.meta_description}}
                </span>
            </p>
        </div>
    </div>
    <div class="box-body" style="display: none;">
        <div class="form-group">
            <label>Url</label>
            <div class="input-group">
                <span class="input-group-addon" style="background-color: #eee;">@{{ appUrl }}/</span>
                <input type="text" ng-model="post.slug" class="form-control" value="ten-san-pham" ng-change="changeSlug()">
            </div>

        </div>
        <div class="form-group">
            <label>Meta title</label>
            {{-- <span class="pull-right">3/70 ký tự</span> --}}
            <input type="text" ng-model="post.meta_title" class="form-control" placeholder="Nhập meta title" ng-change="changeMetaTitle()">
        </div>
        <div class="form-group">
            <label>Meta description</label>
            {{-- <span class="pull-right">3/1000 ký tự</span> --}}
            <textarea type="text" ng-model="post.meta_description" class="form-control"  placeholder="Nhập meta description" ng-change="changeMetaDescription()"></textarea>
        </div>

    </div>
</div>
