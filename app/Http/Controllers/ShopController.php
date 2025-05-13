<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ShopController extends Controller
{
    public function index()
    {
        $products = \App\Models\Product::orderBy('created_at', 'desc')->paginate(10);
        return view('shop', compact('products'));
    }

    public function product_details($product_slug)
    {
        $product = Product::where('slug', $product_slug)->firstOrFail();
        return view('product_details', compact('product'));
    }
}
