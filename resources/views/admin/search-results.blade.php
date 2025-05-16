@extends('layouts.admin')
@section('content')
<div class="main-content-inner">
    <div class="main-content-wrap">
        <div class="flex items-center flex-wrap justify-between gap20 mb-27">
            <h3>Search Results for "{{ $query }}"</h3>
            <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                <li>
                    <a href="{{ route('admin.index') }}">
                        <div class="text-tiny">Dashboard</div>
                    </a>
                </li>
                <li><i class="icon-chevron-right"></i></li>
                <li>
                    <div class="text-tiny">Search Results</div>
                </li>
            </ul>
        </div>

        @if($orders->count() > 0)
        <div class="wg-box mb-4">
            <h4 class="mb-3">Orders</h4>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Payment Method</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td>#{{ $order->id }}</td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="fw-bold">{{ $order->user->name }}</span>
                                    <small class="text-muted">{{ $order->shipping_email }}</small>
                                </div>
                            </td>
                            <td>${{ number_format($order->total_price, 2) }}</td>
                            <td>
                                <span class="badge {{ $order->status === 'delivered' ? 'bg-success' : ($order->status === 'cancelled' ? 'bg-danger' : 'bg-warning') }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td>{{ ucfirst($order->transaction->payment_method) }}</td>
                            <td>{{ $order->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="list-icon-function">
                                    <a href="{{ route('admin.order.details', $order->id) }}">
                                        <div class="item eye">
                                            <i class="icon-eye"></i>
                                        </div>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $orders->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        </div>
        @endif

        @if($products->count() > 0)
        <div class="wg-box">
            <h4 class="mb-3">Products</h4>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Category</th>
                            <th>Brand</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td class="pname">
                                <div class="d-flex align-items-center">
                                    @if($product->image_name)
                                        <img src="{{ asset('uploads/products/' . $product->image_name) }}" 
                                             alt="{{ $product->name }}" 
                                             style="width: 50px; height: 50px; object-fit: cover;"
                                             class="me-2">
                                    @endif
                                    <div>{{ $product->name }}</div>
                                </div>
                            </td>
                            <td>${{ number_format($product->price, 2) }}</td>
                            <td>{{ $product->category->name }}</td>
                            <td>{{ $product->brand->name }}</td>
                            <td>{{ $product->status }}</td>
                            <td>
                                <div class="list-icon-function">
                                    <a href="{{ route('admin.product.edit', $product->id) }}">
                                        <div class="item edit">
                                            <i class="icon-edit"></i>
                                        </div>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $products->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        </div>
        @endif

        @if($orders->count() === 0 && $products->count() === 0)
        <div class="wg-box">
            <div class="alert alert-info">
                No results found for "{{ $query }}"
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
