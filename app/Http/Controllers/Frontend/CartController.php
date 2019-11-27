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
    public function index(Request $request)
    {
        $sessionId = session()->getId();
        $cartItems = [];
        $cart = Cart::where('token', $sessionId)->where('status', 'pending')->first();
        if ($cart) {
            $cartItems = CartItem::where('cart_id', $cart->id)->get();
        }

        return view('frontend.cart.index', compact('cartItems'));
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $sessionId = session()->getId();

        $cart = Cart::where(
            [
                'token' => $sessionId,
                'status' => 'pending'
            ]
        )->first();

        if (empty($cart)) {
            $cart = Cart::create(
                [
                    'token' => $sessionId,
                    'status' => 'pending'
                ]
            );
        }

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

    public function checkout () {
        return view('frontend.cart.checkout');
    }

}
