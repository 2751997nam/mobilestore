<?php
    $width = 122;
?>

<style>
    #images > div {
        float:left;
        margin: 1px;
        padding : 0px;
        width: {{ $width }}px;
        border: 1px solid #ccc;
    }
    #images > div:first-child {
        width: {{ $width * 2 + 2 }}px;
        /* border: 1px solid green; */
        margin: 1px 1px 0px 1px;
    }
    .sb_image {
      position: relative;
      width: 50%;
    }

    .image {
      opacity: 1;
      display: block;
      width: 100%;
      height: auto;
      transition: .5s ease;
      backface-visibility: hidden;
    }

    .middle {
      transition: .5s ease;
      opacity: 0;
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      -ms-transform: translate(-50%, -50%);
      text-align: center;
      width : 100%;
    }

    .sb_image:hover .image {
      opacity: 0.3;
    }

    .sb_image:hover .middle {
      opacity: 1;
    }

    .text {
      background-color: #4CAF50;
      color: white;
      font-size: 16px;
      padding: 16px 32px;
    }

    @media (max-width:480px) {
        #images > div {
            width: 50%;
            margin-right: -1px;

        }
        #images > div:first-child {
            width: 100%;

        }
    }


</style>
<div class="box no-border">
    <form role="form">
        <div class="box-body">
            <h4 class="pull-left">Ảnh</h4>
            <a ngf-select="uploadImages($files)" multiple="multiple" class="pull-right" style="margin-top: 10px;cursor: pointer;">Chọn ảnh</a>
            <br/><br/>
            <div class="text-center" id="images" ng-if="gallery.length > 0">
                <div class="sb_image" ng-repeat="image in gallery">
                    <img ng-src="{{ getImageCdn(image.image_url, <?=$width * 2 ?>, <?=$width * 2 ?>) }}" class="image" style="width:100%">
                    <div class="middle">
                        <button type="button" name="button" class="btn btn-default btm-sm" ng-click="chooseAvatar($index);" title="Đặt làm ảnh đại diện"><i class="fa fa-star"></i></button>
                        <button type="button" name="button" class="btn btn-default btm-sm" ng-click="removeImage($index);" title="Xóa ảnh này"><i class="fa fa-trash"></i></button>
                    </div>
                </div>
            </div>
            <div class="text-center" id="images" ng-if="gallery.length == 0">
                @include('system.products.inc.upload-image-svg')
            </div>
        </div>
    </form>
</div>
