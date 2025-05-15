@extends('layouts.app')
@section('content')
<main class="pt-90">
    <div class="mb-4 pb-4"></div>
    <section class="shop-checkout container">
      <h2 class="page-title">Shipping and Checkout</h2>
      <div class="checkout-steps">
        <a href="{{ route('cart.index') }}" class="checkout-steps__item active">
          <span class="checkout-steps__item-number">01</span>
          <span class="checkout-steps__item-title">
            <span>Shopping Bag</span>
            <em>Manage Your Items List</em>
          </span>
        </a>
        <a href="{{ route('cart.checkout') }}" class="checkout-steps__item active">
          <span class="checkout-steps__item-number">02</span>
          <span class="checkout-steps__item-title">
            <span>Shipping and Checkout</span>
            <em>Checkout Your Items List</em>
          </span>
        </a>
        <a href="{{ route('cart.order.Confirmation') }}" class="checkout-steps__item">
          <span class="checkout-steps__item-number">03</span>
          <span class="checkout-steps__item-title">
            <span>Confirmation</span>
            <em>Review And Submit Your Order</em>
          </span>
        </a>
      </div>
      <form name="checkout-form" method="POST" action="{{ route('cart.placeOrder') }}">
        @csrf
        <div class="checkout-form">
          <div class="billing-info__wrapper">
            <div class="row mt-5">
              <div class="col-md-6">
                <div class="form-floating my-3">
                  <input type="text" class="form-control" name="shipping_name" required value="{{ old('shipping_name', Auth::user()->name ?? '') }}">
                  <label for="shipping_name">Full Name *</label>
                  <span class="text-danger">@error('shipping_name'){{ $message }}@enderror</span>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-floating my-3">
                  <input type="text" class="form-control" name="shipping_phone" required value="{{ old('shipping_phone') }}">
                  <label for="shipping_phone">Phone Number *</label>
                  <span class="text-danger">@error('shipping_phone'){{ $message }}@enderror</span>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-floating my-3">
                  <input type="text" class="form-control" name="shipping_address" required value="{{ old('shipping_address') }}">
                  <label for="shipping_address">Address *</label>
                  <span class="text-danger">@error('shipping_address'){{ $message }}@enderror</span>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-floating my-3">
                  <input type="email" class="form-control" name="shipping_email" required value="{{ old('shipping_email', Auth::user()->email ?? '') }}">
                  <label for="shipping_email">Email *</label>
                  <span class="text-danger">@error('shipping_email'){{ $message }}@enderror</span>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-floating my-3">
                  <input type="text" class="form-control" name="shipping_note" value="{{ old('shipping_note') }}">
                  <label for="shipping_note">Order Note (optional)</label>
                </div>
              </div>
            </div>
          </div>
          <div class="checkout__totals-wrapper">
            <div class="sticky-content">
              <div class="checkout__totals">
                <h3>Your Order</h3>
                <table class="checkout-cart-items">
                  <thead>
                    <tr>
                      <th>PRODUCT</th>
                      <th align="right">SUBTOTAL</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($cartItems as $item)
                    <tr>
                      <td>
                        {{ $item->name }} x {{ $item->qty }}
                      </td>
                      <td align="right">
                        ${{ number_format($item->price * $item->qty, 2) }}
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
                <table class="checkout-totals">
                  <tbody>
                    <tr>
                      <th>SUBTOTAL</th>
                      <td align="right">${{ Cart::subtotal() }}</td>
                    </tr>
                    <tr>
                      <th>SHIPPING</th>
                      <td align="right">Free shipping</td>
                    </tr>
                    <tr>
                      <th>VAT</th>
                      <td align="right">${{ Cart::tax() }}</td>
                    </tr>
                    <tr>
                      <th>TOTAL</th>
                      <td align="right">${{ Cart::total() }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div class="checkout__payment-methods">
                <div class="form-check">
                  <input class="form-check-input form-check-input_fill" type="radio" name="payment_method" value="bank_transfer" id="checkout_payment_method_1" checked>
                  <label class="form-check-label" for="checkout_payment_method_1">
                    Direct bank transfer
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input form-check-input_fill" type="radio" name="payment_method" value="check" id="checkout_payment_method_2">
                  <label class="form-check-label" for="checkout_payment_method_2">
                    Check payments
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input form-check-input_fill" type="radio" name="payment_method" value="cod" id="checkout_payment_method_3">
                  <label class="form-check-label" for="checkout_payment_method_3">
                    Cash on delivery
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input form-check-input_fill" type="radio" name="payment_method" value="paypal" id="checkout_payment_method_4">
                  <label class="form-check-label" for="checkout_payment_method_4">
                    Paypal
                  </label>
                </div>
                <div class="policy-text">
                  Your personal data will be used to process your order, support your experience throughout this
                  website, and for other purposes described in our <a href="#" target="_blank">privacy
                    policy</a>.
                </div>
              </div>
              <button class="btn btn-primary btn-checkout" type="submit">PLACE ORDER</button>
            </div>
          </div>
        </div>
      </form>
    </section>
  </main>
@endsection