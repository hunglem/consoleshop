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
            return redirect()->route('login')->with('error', 'Please login to proceed to checkout.');
        }
        $address = address::where('user_id', Auth::id())->get();
        $cartItems = Cart::content();
        return view('checkout', compact('address', 'cartItems'));
    }

    public function placeOrder(Request $request)
    {
        $user_id = Auth::id();
        $address = address::where('user_id', $user_id)->where('is_default', true)->first();
        if (!$address) {
            $request->validate([
                'shipping_name' => 'required',
                'shipping_phone' => 'required',
                'shipping_address' => 'required',
            ]);
            $address = new address();
            $address->name = $request->shipping_name;
            $address->phone = $request->shipping_phone;
            $address->address = $request->shipping_address;
            $address->user_id = $user_id;
            $address->is_default = true;
            $address->save();
        }
        $this->setAmountforCheckout();

        $order = new order();
        $order->user_id = $user_id;
        $order->total_price = Session::get('checkout')['total'] ?? 0;
        $order->shipping_name = $address->name;
        $order->shipping_phone = $address->phone;
        $order->shipping_address = $address->address;
        $order->shipping_email = $request->shipping_email;
        $order->shipping_note = $request->shipping_note;
        $order->save();

        foreach (Cart::content() as $item) {
            $orderItem = new order_detail();
            $orderItem->order_id = $order->id;
            $orderItem->product_id = $item->id;
            $orderItem->quantity = $item->qty;
            $orderItem->price = $item->price;
            $orderItem->save();
        }

        $transaction = new transaction();
        $transaction->user_id = $user_id;
        $transaction->order_id = $order->id;
        $transaction->status = 'pending';
        $transaction->payment_method = $request->payment_method;
        $transaction->save();

        Cart::instance('cart')->destroy();
        Session::forget('checkout');
        Session::put('order_id', $order->id);
        return redirect()->route('cart.order.Confirmation');
    }

    public function setAmountforCheckout()
    {
        if (!Cart::instance('cart')->count() > 0) {
            Session::forget('checkout');
            return;
        } else {
            $cartItems = Cart::content();
            $total = 0;
            foreach ($cartItems as $item) {
                $total += $item->price * $item->qty;
            }
            Session::put('checkout', [
                'subtotal' => Cart::instance('cart')->subtotal(),
                'tax' => Cart::instance('cart')->tax(),
                'total' => Cart::instance('cart')->total(),
            ]);
        }
    }

    public function orderConfirmation()
    {
        if (Session::has('order_id')) {
            $orderId = Session::get('order_id');
            $order = order::with('transaction')->find($orderId);
            if ($order) {
                return view('order_Confirmation', compact('order'));
            }
        }
        return redirect()->route('cart.index');
    }
}
