<?php
    $title = isset($title) ? $title : "";
    $description = isset($description) ? $description : "";
    $buttonUrl = isset($buttonUrl) ? $buttonUrl : "";
    $buttonText = isset($buttonText) ? $buttonText : "";
    $imageUrl = isset($imageUrl) ? $imageUrl : "";
?>
<div class="" style="display: relative !important; min-height: 80px;">
    <div class="col-md-8" style="min-height: 80px;">
        <h3>{{ $title }}</h3>
        <p>{{ $description }}</p>
        <a href="{{ $buttonUrl }}"  target="blank" type="button" name="button" class="btn btn-flat btn-success">{{ $buttonText }}</a>

    </div>
    @if (isset($imageUrl) && $imageUrl)
        <div class="col-md-4 hidden-xs">
            <img src="{{ $imageUrl }}" alt="">
        </div>
    @endif

    <div class="clearfix">
    </div>
    {{-- <div class="help-box" style="display: absolute !important;">
        <a href="#"><i class="fa fa-home"></i> Tìm hiểu thêm</a>
    </div> --}}
</div>
