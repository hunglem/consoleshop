@extends('layouts.app')
@section('content')
<main class="checkout-page pt-90 pb-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-7">
                <div class="checkout-form bg-white p-4 rounded shadow-sm mb-4">
                    <h2 class="mb-4"></h2>
                    <form action="{{ route('order.place') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="shipping_name" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="shipping_name" name="shipping_name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="shipping_phone" class="form-label">Phone</label>
                                <input type="text" class="form-control" id="shipping_phone" name="shipping_phone" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="shipping_email" class="form-label">Email (optional)</label>
                            <input type="email" class="form-control" id="shipping_email" name="shipping_email">
                        </div>
                        <div class="mb-3">
                            <label for="shipping_address" class="form-label">Address</label>
                            <input type="text" class="form-control" id="shipping_address" name="shipping_address" required>
                        </div>
                        <div class="mb-3">
                            <label for="shipping_note" class="form-label">Note (optional)</label>
                            <textarea class="form-control" id="shipping_note" name="shipping_note" rows="2"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="payment_method" class="form-label">Payment Method</label>
                            <select class="form-select" id="payment_method" name="payment_method" required>
                                <option value="credit_card">Credit Card</option>
                                <option value="paypal">PayPal</option>
                                <option value="bank_transfer">Bank Transfer</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Place Order</button>
                    </form>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="order-summary bg-white p-4 rounded shadow-sm">
                    <h2 class="mb-4">Order Summary</h2>
                    @php $total = 0; @endphp
                    @if(count($cartItems) > 0)
                        <ul class="list-group mb-3">
                            @foreach($cartItems as $item)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <strong>{{ $item->name ?? 'Product' }}</strong><br>
                                            <small>Qty: {{ $item->qty ?? 1 }}</small>
                                        </div>
                                    </div>
                                    <span>${{ number_format(($item->price ?? 0) * ($item->qty ?? 1), 2) }}</span>
                                </li>
                                @php $total += ($item->price ?? 0) * ($item->qty ?? 1); @endphp
                            @endforeach
                        </ul>
                        <div class="d-flex justify-content-between border-top pt-3">
                            <span class="fw-bold">Total:</span>
                            <span class="fw-bold">${{ number_format($total, 2) }}</span>
                        </div>
                    @else
                        <p>Your cart is empty.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</main>
@endsection