<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller 
{
    public function index(Request $request)
    {
        $categories = Category::orderBy('name', 'asc')->get();

        return $this->responseData($categories);
    }
}
