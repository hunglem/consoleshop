<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index()
    {   
        $products = Product::orderBy('created_at', 'desc')->paginate(10);
        return view('shop', compact('products'));
    }

    
}
