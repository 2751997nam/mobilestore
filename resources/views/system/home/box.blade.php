<?php
    $title = isset($title) ? $title : "";
    $description = isset($description) ? $description : "";
    $buttonUrl = isset($buttonUrl) ? $buttonUrl : "";
    $buttonText = isset($buttonText) ? $buttonText : "";
?>
<div class="sb-box">
    <h4>{{ $title }}</h4>
    <p>{!! $description  !!}</p>
    <div class="sb-box-action">
        <a href="{{ $buttonUrl }}" target="blank" type="button" name="button" class="btn btn-success btn-flat">{{ $buttonText }}</a>
    </div>
</div>
