<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $newestProducts = $this->getNewestProducts();

        return view('frontend.index', compact('newestProducts'));
    }

    public function getNewestProducts()
    {
        return Product::where('status', 'ACTIVE')->orderBy('created_at', 'desc')->take(config('frontend.item_limit'))->get();
    }

}
