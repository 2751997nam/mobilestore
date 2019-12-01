<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Category;
use Illuminate\Support\Facades\View;

class HeaderMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $categories = Category::where('is_display_home_page', '1')
                            ->where('is_hidden', 0)
                            ->get();
        View::share('categories', $categories);

        return $next($request);
    }
}
