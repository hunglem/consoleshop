<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Surfsidemedia\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\address;
use App\Models\product;
use App\Models\order;
use App\Models\order_detail;
use App\Models\transaction;
use App\Models\User;

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
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        $address = address::where('user_id', Auth::id())->get();
        $cartItems = Cart::content();
        return view('checkout', compact('address', 'cartItems'));
    }

    public function orderConfirmation()
    {
        if (Session::has('order_id')) {
            $orderId = Session::get('order_id');
            $order = order::with(['orderDetails.product', 'transaction'])->find($orderId);
            if ($order) {
                // Calculate subtotal and VAT for display
                $subtotal = 0;
                foreach ($order->orderDetails as $item) {
                    $subtotal += $item->price * $item->quantity;
                }
                $vat = $subtotal * 0.1; // Example: 10% VAT
                $grandTotal = $subtotal + $vat;
                return view('order_Confirmation', compact('order', 'subtotal', 'vat', 'grandTotal'));
            }
        }
        return redirect()->route('cart.index');
    }

    public function placeOrder(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Validate request
        $request->validate([
            'shipping_name' => 'required',
            'shipping_phone' => 'required',
            'shipping_address' => 'required',
            'shipping_email' => 'required|email',
        ]);

        $cartItems = Cart::content();
        if ($cartItems->count() === 0) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        // Calculate totals
        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += $item->price * $item->qty;
        }
        $vat = $subtotal * 0.1; // 10% VAT
        $total = $subtotal + $vat;

        // Create order
        $order = new order();
        $order->user_id = Auth::id();
        $order->total_price = $total;
        $order->shipping_name = $request->shipping_name;
        $order->shipping_phone = $request->shipping_phone;
        $order->shipping_address = $request->shipping_address;
        $order->shipping_email = $request->shipping_email;
        $order->shipping_note = $request->shipping_note;
        $order->status = 'order';
        $order->save();

        // Create order details
        foreach ($cartItems as $item) {
            $orderDetail = new order_detail();
            $orderDetail->order_id = $order->id;
            $orderDetail->product_id = $item->id;
            $orderDetail->quantity = $item->qty;
            $orderDetail->price = $item->price;
            $orderDetail->save();
        }

        // Create transaction
        $transaction = new transaction();
        $transaction->user_id = Auth::id();
        $transaction->order_id = $order->id;
        $transaction->status = 'pending';
        $transaction->payment_method = $request->payment_method ?? 'cod';
        $transaction->save();

        // Clear cart and save order ID in session
        Cart::destroy();
        Session::forget('checkout');
        Session::put('order_id', $order->id);

        return redirect()->route('cart.order.Confirmation');
    }
}