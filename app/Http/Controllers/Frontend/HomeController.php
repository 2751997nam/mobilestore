<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;

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

        $popularCategories = $this->getPopularCategories();

        return view('frontend.index', compact('newestProducts', 'popularCategories'));
    }

    public function getNewestProducts()
    {
        return Product::where('status', 'ACTIVE')->orderBy('created_at', 'desc')->take(config('frontend.item_limit'))->get();
    }

    public function getPopularCategories() {
        $categories = Category::with(['products' => function ($query) {
                $query->where('status', 'ACTIVE')
                    ->limit(10);
            }])
            ->where('is_display_home_page', 1)
            ->where('type', 'PRODUCT')
            ->where('is_hidden', '!=', 1)
            ->get();

        return $categories;
    }

    public function recentViewed(Request $request)
    {
        $productIds = $request->get('productIds', '');
        if (!empty($productIds)) {
            $recentProducts = Product::whereIn('id', $productIds)->where('status', 'active')->limit(10)->get();
            return view('frontend.common.recent-viewed', compact('recentProducts'));
        }

        return '';
    }

}
