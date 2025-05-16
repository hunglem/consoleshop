@extends('layouts.admin')
@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Order Details #{{ $order->id }}</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li>
                    <a href="{{ route('admin.index') }}">
                        <div class="text-tiny">Dashboard</div>
                    </a>
                </li>
                <li><i class="icon-chevron-right"></i></li>
                <li>
                    <a href="{{ route('admin.orders') }}">
                        <div class="text-tiny">Orders</div>
                    </a>
                </li>
                <li><i class="icon-chevron-right"></i></li>
                <li>
                    <div class="text-tiny">Order Details</div>
                </li>
            </ul>
        </div>

        <!-- Order Status -->
        <div class="wg-box">
            <h4 class="mb-3">Order Status</h4>
            <form action="{{ route('admin.order.status.update', $order->id) }}" method="POST" class="d-flex align-items-center">
                @csrf
                @method('PUT')
                <select name="status" class="form-select me-2" style="width: 200px;">
                    <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                    <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                <button type="submit" class="btn btn-primary">Update Status</button>
            </form>
        </div>

        <!-- Order Information -->
        <div class="wg-box mt-4">
            <h4 class="mb-3">Order Information</h4>
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Customer Name:</strong> {{ $order->user->name }}</p>
                    <p><strong>Email:</strong> {{ $order->shipping_email }}</p>
                    <p><strong>Phone:</strong> {{ $order->shipping_phone }}</p>
                    <p><strong>Shipping Address:</strong> {{ $order->shipping_address }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Order Date:</strong> {{ $order->created_at->format('M d, Y H:i') }}</p>
                    <p><strong>Payment Method:</strong> {{ ucfirst($order->transaction->payment_method) }}</p>
                    <p><strong>Payment Status:</strong> {{ ucfirst($order->transaction->status) }}</p>
                    @if($order->shipping_note)
                        <p><strong>Note:</strong> {{ $order->shipping_note }}</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="wg-box mt-4">
            <h4 class="mb-3">Order Items</h4>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th class="text-center">Price</th>
                            <th class="text-center">Quantity</th>
                            <th class="text-center">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->orderDetails as $item)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($item->product->image_name)
                                        <img src="{{ asset('uploads/products/thumbnail/'.$item->product->image_name) }}" 
                                             alt="{{ $item->product->name }}" 
                                             style="width: 50px; height: 50px; object-fit: cover;"
                                             class="me-2">
                                    @endif
                                    <div>{{ $item->product->name }}</div>
                                </div>
                            </td>
                            <td class="text-center">${{ number_format($item->price, 2) }}</td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-center">${{ number_format($item->price * $item->quantity, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-end"><strong>Total:</strong></td>
                            <td class="text-center"><strong>${{ number_format($order->total_price, 2) }}</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection