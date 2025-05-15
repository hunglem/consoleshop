@extends('layouts.app')
@section('content')
<main class="pt-90">
    <div class="mb-4 pb-4"></div>
    <section class="shop-checkout container">
      <h2 class="page-title">Cart</h2>
      <div class="checkout-steps">
        <a href="{{ route('cart.index') }}" class="checkout-steps__item active">
          <span class="checkout-steps__item-number">01</span>
          <span class="checkout-steps__item-title">
            <span>Shopping Bag</span>
            <em>Manage Your Items List</em>
          </span>
        </a>
        <a href="checkout.html" class="checkout-steps__item">
          <span class="checkout-steps__item-number">02</span>
          <span class="checkout-steps__item-title">
            <span>Shipping and Checkout</span>
            <em>Checkout Your Items List</em>
          </span>
        </a>
        <a href="order-confirmation.html" class="checkout-steps__item">
          <span class="checkout-steps__item-number">03</span>
          <span class="checkout-steps__item-title">
            <span>Confirmation</span>
            <em>Review And Submit Your Order</em>
          </span>
        </a>
      </div>
      <div class="shopping-cart">
        <div class="cart-table__wrapper">
          <form action="#" method="post">
            @csrf
            <table class="cart-table">
              <thead>
                <tr>
                  <th>Product</th>
                  <th></th>
                  <th>Price</th>
                  <th>Quantity</th>
                  <th>Subtotal</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                @php
                  $subtotal = 0;
                @endphp
                @forelse($cartItems as $item)
                  @php
                    $itemSubtotal = $item->price * $item->qty;
                    $subtotal += $itemSubtotal;
                    $slug = $item->attributes && isset($item->attributes->slug) ? $item->attributes->slug : null;
                    if ($item->attributes && isset($item->attributes->image) && !empty($item->attributes->image)) {
                        $image = $item->attributes->image;
                    } else {
                        $image = asset('assets/images/products/product_0.png');
                    }
                  @endphp
                  <tr>
                    <td>
                      <div class="shopping-cart__product-item">
                        <a href="{{ $slug ? route('shop.product_details', ['product_slug' => $slug]) : '#' }}">
                          <img loading="lazy" src="{{ $image }}" width="120" height="120" alt="{{ $item->name }}" />
                        </a>
                      </div>
                    </td>
                    <td>
                      <div class="shopping-cart__product-item__detail">
                        <h4>
                          <a href="{{ $slug ? route('shop.product_details', ['product_slug' => $slug]) : '#' }}">
                            {{ $item->name }}
                          </a>
                        </h4>
                        {{-- Add options if available --}}
                      </div>
                    </td>
                    <td>
                      <span class="shopping-cart__product-price">${{ number_format($item->price, 2) }}</span>
                    </td>
                    <td>
                      <div class="qty-control position-relative">
                        <form action="{{ route('cart.update', $item->rowId) }}" method="post" style="display:inline-flex;">
                          @csrf
                          @method('PUT')
                          <input type="number" name="quantity" value="{{ $item->qty }}" min="1" class="qty-control__number text-center" style="width:60px;">
                          <button type="submit" class="btn btn-sm btn-light ms-2">Update</button>
                        </form>
                      </div>
                    </td>
                    <td>
                      <span class="shopping-cart__subtotal">${{ number_format($itemSubtotal, 2) }}</span>
                    </td>
                    <td>
                      <form action="{{ route('cart.remove', $item->rowId) }}" method="post">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="remove-cart btn btn-link p-0">
                          <svg width="10" height="10" viewBox="0 0 10 10" fill="#767676" xmlns="http://www.w3.org/2000/svg">
                            <path d="M0.259435 8.85506L9.11449 0L10 0.885506L1.14494 9.74056L0.259435 8.85506Z" />
                            <path d="M0.885506 0.0889838L9.74057 8.94404L8.85506 9.82955L0 0.97449L0.885506 0.0889838Z" />
                          </svg>
                        </button>
                      </form>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="6" class="text-center">Your cart is empty.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </form>
          <div class="cart-table-footer">
            
            <a href="{{ route('cart.index') }}" class="btn btn-light">UPDATE CART</a>
          </div>
        </div>
        <div class="shopping-cart__totals-wrapper">
          <div class="sticky-content">
            <div class="shopping-cart__totals">
              <h3>Cart Totals</h3>
              <table class="cart-totals">
                <tbody>
                  <tr>
                    <th>Subtotal</th>
                    <td>${{ number_format($subtotal, 2) }}</td>
                  </tr>
                  <tr>
                    <th>Shipping</th>
                    <td>
                      <div class="form-check">
                        <input class="form-check-input form-check-input_fill" type="checkbox" value=""
                          id="free_shipping">
                        <label class="form-check-label" for="free_shipping">Free shipping</label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input form-check-input_fill" type="checkbox" value="" id="flat_rate">
                        <label class="form-check-label" for="flat_rate">Flat rate: $49</label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input form-check-input_fill" type="checkbox" value=""
                          id="local_pickup">
                        <label class="form-check-label" for="local_pickup">Local pickup: $8</label>
                      </div>
                      <div>Shipping to AL.</div>
                      <div>
                        <a href="#" class="menu-link menu-link_us-s">CHANGE ADDRESS</a>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <th>VAT</th>
                    @php
                      $vat = round($subtotal * 0.21, 2); // 21% VAT as per config
                    @endphp
                    <td>${{ number_format($vat, 2) }}</td>
                  </tr>
                  <tr>
                    <th>Total</th>
                    @php
                      $total = $subtotal + $vat;
                    @endphp
                    <td>${{ number_format($total, 2) }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="mobile_fixed-btn_wrapper">
              <div class="button-wrapper container">
                <a href="checkout.html" class="btn btn-primary btn-checkout">PROCEED TO CHECKOUT</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
@endsection