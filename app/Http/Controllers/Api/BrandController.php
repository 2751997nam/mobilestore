<?php

namespace App\Http\Controllers\Api;

use App\Models\Brand;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BrandController extends Controller 
{
    public function index(Request $request)
    {
        $brands = Brand::orderBy('name', 'asc')->get();

        return $this->responseData($brands);
    }
}
