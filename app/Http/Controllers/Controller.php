<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function responseData($data)
    {
        return [
            'status' => 'successful',
            'result' => $data
        ];
    }

    public function responseFail($message)
    {
        return [
            'status' => 'fail',
            'message' => $message
        ];
    }

    public function responseSuccess($message)
    {
        return [
            'status' => 'successful',
            'message' => $message
        ];
    }

    public function responseWithMeta($data, $meta)
    {
        return [
            'status' => 'successful',
            'result' => $data,
            'meta' => $meta
        ];
    }

    public function getMetaData($query, Request $request)
    {
        $meta = [
            'total_count' => 0,
            'page_size' => 20,
            'page_id' => 0,
            'has_next' => false,
            'page_count' => 0
        ];
        $total = $query->count();

        $meta['total_count'] = $total;

        if ($request->has('page_id')) {
            $meta['page_id'] = (int) $request->page_id;
        }

        if ($request->has('page_size')) {
            $meta['page_size'] = (int) $request->page_size;
        }

        $meta['page_count'] = ceil($total / $meta['page_size']);
        if ($meta['page_id'] < $meta['page_count'] - 1) {
            $meta['has_next'] = true;
        }
        
        return $meta;
    }

    public function paginate($query, $pageSize, $pageId = null)
    {
        if (func_num_args() == 2) {
            $size = $pageSize->get('page_size', 20);
            $offset = $size * $pageSize->get('page_id', 0);
            return $query->limit($size)->offset($offset)->get();
        }

        return $query->limit($pageSize)->offset($pageSize * $pageId)->get();
    }
}
