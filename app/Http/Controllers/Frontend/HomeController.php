<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

        $popularCategories = $this->getPopularCategories();

        $discountProducts = $this->getDiscountProducts();

        $viewedProducts = $this->getViewedProducts();

        return view('frontend.index', compact('newestProducts', 'popularCategories', 'discountProducts', 'viewedProducts'));
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

    public function getDiscountProducts()
    {
        $products = Product::select('*')
                ->addSelect(DB::raw('(price/high_price) as discount'))
                ->where('price', '>', 0)
                ->where('high_price', '>', 0)
                ->orderBy('discount', 'asc')
                ->limit(6)
                ->get();

        return $products;
    }

    public function getViewedProducts()
    {
        $products = Product::orderBy('view_count', 'desc')->limit(8)->get();

        return $products;
    }

}
