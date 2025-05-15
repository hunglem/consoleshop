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
        $user_id = Auth::id();
        $address_id = Address::where('user_id', $user_id)->where('is_default', true)->first();
        if (!$address_id) {
            $request->validate([
                'name' => 'required',
                'phone' => 'required',
                'address' => 'required',
            ]);
            $address = new Address();
            $address->name = $request->name;
            $address->phone = $request->phone;
            $address->address = $request->address;
            $address->user_id = $user_id;
            $address->is_default = true;
            $address->save();

            return redirect()->route('cart.order.Confirmation',compact('order'));
        }
        $this->setAmountforCheckout();


        $order = new Order();
        $order->user_id = $user_id;
        $order->total_price = Session::get('checkout')['total'];
        $order->name = $address->name;
        $order->phone = $address->phone;    
        $order->address = $address->address;
        $order->save();

        foreach (Cart::content() as $item) {
            $orderItem = new OrderItem();
            $orderItem->order_id = $order->id;
            $orderItem->product_id = $item->id;
            $orderItem->quantity = $item->qty;
            $orderItem->price = $item->price;
            $orderItem->save();
        }

        $transaction = new Transaction();
        $transaction->user_id = $user_id;
        $transaction->order_id = $order->id;
        $transaction->status = 'pending';
        $transaction->payment_method = $request->payment_method;
        $transaction->save();

        Cart::instance('cart')->destroy();
        Session::forget('checkout');
        Session::put('order_id', $order->id);
        return view('order_confirmation', compact('order'));

   }
   

   public function setAmountforCheckout()
   {
    if(!Cart::instance('cart')->count() > 0)
    {
        Seession::forget('checkout');
        return;
    }
    else
    {
        $cartItems = Cart::content();
        $total = 0;
        foreach($cartItems as $item)
        {
            $total += $item->price * $item->qty;
        }
        Session::put('checkout', [
            'subtotal' => Cart::instance('cart')->subtotal(),
            'tax' => Cart::instance('cart')->tax(),
            'total' => Cart::instance('cart')->total(),
        ]);
    }
   }
    public function order_confirmation()
    {   
        if (Session::has('order_id')) {
            $order = Session::find('order_id');
            return view('order_confirmation', compact('order'));
        }
            
        return redirect()->route('cart.index');
    }
}
