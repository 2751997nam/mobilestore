<?php

function formatPrice($price)
{
    return number_format($price, 0, ',', '.') . ' ₫';
}

function getPaginationPageUrl($pageId)
{
    $parameters = $_SERVER['QUERY_STRING'];
    $parameters = explode('&', $parameters);
    $url = url()->current() . '?';
    $check = true;
    $count = count($parameters);
    $last =  $parameters[$count - 1];
    foreach ($parameters as $value) {
        if (strpos($value, 'page_id') === 0) {
            $check = false;
            $url .= 'page_id=' . $pageId;
        } else {
            $url .= $value;
        }
        if ($value != $last) {
            $url .= '&';
        }
    }

    if ($check) {
        $url .= '&page_id=' . $pageId;
    }

    return $url;
}