<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Surfsidemedia\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use App\Models\address;
use App\Models\product;

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
            'qty' => $request->quantity ?? 1,
            'options' => [
                'image' => $request->image,
                'slug' => $request->slug ?? null,
            ],
        ]);

        return redirect()->route('cart.index')->with('success', 'Item added to cart!');
    }

    public function update(Request $request, $rowId)
    {
        $quantity = $request->input('quantity', 1);
        Cart::update($rowId, $quantity);
        return redirect()->route('cart.index')->with('success', 'Cart updated successfully!');
    }

    public function remove($rowId)
    {
        Cart::remove($rowId);
        return redirect()->route('cart.index')->with('success', 'Item removed from cart!');
    }

    public function checkout()
    {
        if (!Auth::check()) 
        {
            return redirect()->route('login')->with('error', 'Please login to proceed to checkout.');
        } 
        $address = address::where('user_id', Auth::id())->get();
        // Get cart items from the Cart package, not from session
        $cartItems = \Surfsidemedia\Shoppingcart\Facades\Cart::content();
        return view('checkout', compact('address', 'cartItems'));
    }

    public function placeOrder(Request $request)
    {
        $request->validate([
            'payment_method' => 'required',
        ]);

        $user = Auth::user();
        $cartItems = \Surfsidemedia\Shoppingcart\Facades\Cart::content();
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        // For simplicity, use the first address
        $address = $user->address()->first();
        if (!$address) {
            return redirect()->route('cart.checkout')->with('error', 'Please add a shipping address.');
        }

        // Create order
        $order = new \App\Models\order();
        $order->status = 'order';
        $order->shipping_address = $address->address;
        $order->shipping_phone = $address->phone;
        $order->shipping_email = $user->email;
        $order->shipping_name = $address->name;
        $order->users_id = $user->id;
        $order->save();

        // Create order details
        foreach ($cartItems as $item) {
            $order->orderDetails()->create([
                'product_id' => $item->id,
                'price' => $item->price,
                'quantity' => $item->qty,
            ]);
        }

        // Create transaction
        $order->transaction()->create([
            'user_id' => $user->id,
            'status' => 'pending',
            'payment_method' => $request->payment_method,
        ]);

        // Clear cart
        \Surfsidemedia\Shoppingcart\Facades\Cart::destroy();

        return redirect()->route('home.index')->with('success', 'Order placed successfully!');
    }
}
