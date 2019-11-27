<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;

class IndexController extends Controller
{
    public function index()
    {
        return view('system.home.index');
    }

    public function module ($module) {
        $view = "system.$module.index";
        if(view()->exists($view)){
            return view($view);
        } else {
            App::abort(404, "View $view not found!");
        }
    }

    public function subModule ($module, $subModule) {
        $view =  ("system.$module.$subModule");
        if ($module == 'products' && $subModule == 'import') {
            $view = ("system.$module.import");
        }
        if ($module == 'products' && $subModule == 'categories') {
            View::share('categoryType', 'PRODUCT');
            $view = ("system.$subModule.index");
        }
        if ($module == 'products' && !in_array($subModule, ['new', 'import', 'categories'])) {
            View::share('id', $subModule);
            $view = ("system.$module.new");
        }
        if ( $module == 'customers' && $subModule != 'new' ) {
            View::share('id', $subModule);
            $view = ("system.$module.detail");
        }
        if ( $module == 'posts' && !in_array($subModule, ['new', 'categories'])) {
            View::share('id', $subModule);
            $view = ("system.$module.new");
        }
        if ($module == 'posts' && $subModule == 'categories') {
            View::share('categoryType', 'POST');
            $view = ("system.$subModule.index");
        }
        if ($module == 'orders' && $subModule != 'new' && is_numeric($subModule)) {
            View::share('id', $subModule);
            $view = ("system.$module.edit");
        }
        if ($module == 'filters' && $subModule != 'new' && is_numeric($subModule)) {
            View::share('id', $subModule);
            $view = ("system.$module.new");
        }
        if (view()->exists($view)) {
            return view($view);
        } else {
            App::abort(404, "View $view not found!");
        }
    }
}
