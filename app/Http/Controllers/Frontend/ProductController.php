<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request, $slug = null, $id = null)
    {
        if (!empty($id)) {
            $request->merge(['category' => [$id]]);
        }

        $data = \App::call('App\Http\Controllers\Api\ProductController@search', $request->all());
        // dd($data);
        return view('frontend.product.search', compact('data'));
    }

    public function getNewestProducts()
    {
        return Product::where('status', 'ACTIVE')->orderBy('created_at', 'desc')->take(config('frontend.item_limit'))->get();
    }

    public function show($slug, $id)
    {
        $product = Product::with(['galleries', 'categories'])->where('id', $id)->first();
        $product->view_count = $product->view_count + 1;
        $product->save();

        return view('frontend.product.detail', compact('product'));
    }

}
