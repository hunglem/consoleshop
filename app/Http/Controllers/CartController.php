<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Surfsidemedia\Shoppingcart\Facades\Cart;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Cart::content();
        return view('cart', compact('cartItems'));
    }

    public function add(Request $request)
    {
        $item = Cart::add([
            'id' => $request->id,
            'name' => $request->name,
            'price' => $request->price,
            'quantity' => $request->quantity ?? 1,
            'attributes' => [
                'image' => $request->image,
                'slug' => $request->slug,
            ],
        ]);

        return redirect()->route('cart.index')->with('success', 'Item added to cart!');
    }

    public function remove($id)
    {
        Cart::remove($id);
        return redirect()->route('cart.index')->with('success', 'Item removed from cart!');
    }

    public function clear()
    {
        Cart::clear();
        return redirect()->route('cart.index')->with('success', 'Cart cleared!');
    }
}
