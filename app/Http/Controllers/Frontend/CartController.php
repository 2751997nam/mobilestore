<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Cart;
use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CartController extends Controller
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

    public function store(Request $request)
    {
        $data = $request->all();
        $sessionId = session()->getId();

        $cart = Cart::updateOrCreate(
            [
                'token' => $sessionId
            ],
            [
                'status' => 'pending'
            ]
        );

        $cartItems = CartItem::where('cart_id', $cart->id)->get();
        $check = true;
        foreach($cartItems as $item) {
            if ($item->product_id == $data['product_id']) {
                $item->quantity += $data['quantity'];
                $item->save();
                $check = false;
                break;
            }
        }

        if ($check) {
            $product = Product::find($data['product_id']);
            if ($product) {
                $cartItem = CartItem::create([
                    'cart_id' => $cart->id,
                    'product_id' => $product->id,
                    'price' => $product->price,
                    'quantity' => $data['quantity'],
                    'discount' => ($product->high_price >= $product->price) ? ($product->high_price - $product->price) : 0,
                    'product_name' => $product->name,
                    'image_url' => $product->image_url
                ]);
                $cartItems->push($cartItem);
            }
        }

        return view('frontend.cart.preview', compact('cartItems'));
    }

    public function previewCart() {
        $sessionId = session()->getId();
        $cartItems = [];
        $cart = Cart::where('token', $sessionId)->where('status', 'pending')->first();
        if ($cart) {
            $cartItems = CartItem::where('cart_id', $cart->id)->get();
        }

        return view('frontend.cart.preview', compact('cartItems'));
    }

}
