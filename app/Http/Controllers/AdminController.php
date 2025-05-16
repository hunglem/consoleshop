<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use App\Services\RoleServiceprovider;
use App\Models\User;
use App\Models\Role;    
use App\Models\Category;
use App\Models\Brand;
use Itervention\Image\Facades\Image;
use App\Models\Product;
use App\Models\Order;


class AdminController extends Controller
{
    public function index()
    {
        return view('admin.index');
    }

    public function dashboard()
    {
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'order')->count();
        $deliveredOrders = Order::where('status', 'delivered')->count();
        $cancelledOrders = Order::where('status', 'cancelled')->count();
        
        return view('admin.dashboard', compact('totalOrders', 'pendingOrders', 'deliveredOrders', 'cancelledOrders'));
    }
    
    public function brands()
    {
        $brands = Brand::all();
        return view('admin.brands', compact('brands'));
    }

    public function create_Brand()
    {
        return view('admin.brand-create');
    }

    public function edit_Brand($id)
    {
        $Brand = Brand::findOrFail($id); // Retrieve the brand by ID
        return view('admin.brand-edit', compact('Brand')); // Pass the $Brand variable to the view
    }

    public function update_Brand(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $brand = Brand::findOrFail($request->id);
        $brand->name = $request->name;

        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if (File::exists(public_path('uploads/brands/' . $brand->image))) {
                File::delete(public_path('uploads/brands/' . $brand->image));
            }

            $image = $request->file('image');
            $fileName = time() . '.' . $image->getClientOriginalExtension(); // Correct method
            $image->move(public_path('uploads/brands'), $fileName); // Move the file
            $brand->image = $fileName;
        }

        $brand->save();

        return redirect()->route('admin.brands')->with('success', 'Brand updated successfully!');
    }

    public function store_Brand(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $brand = new Brand();
        $brand->name = $request->name;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $fileName = time() . '.' . $image->getClientOriginalExtension(); // Correct method
            $image->move(public_path('uploads/brands'), $fileName); // Move the file
            $brand->image = $fileName;
        }

        $brand->save();

        return redirect()->route('admin.brands')->with('success', 'Brand created successfully!');
    }

    public function GenarateThumbnailImage($fileName, $image)
    {
        $thumbnailPath = public_path('uploads/brands/thumbnails/' . $fileName);
        $img = Image::make($image->getRealPath());
        $img->resize(150, 150, function ($constraint) {
            $constraint->aspectRatio();
        })->save($thumbnailPath);
    }   
    public function delete_Brand($id)
    {
        $brand = Brand::findOrFail($id);

        // Delete the image file if it exists
        if (File::exists(public_path('uploads/brands/' . $brand->image))) {
            File::delete(public_path('uploads/brands/' . $brand->image));
        }

        // Delete the brand record
        $brand->delete();

        return redirect()->route('admin.brands')->with('success', 'Brand deleted successfully!');
    }

    public function categories()
    {
        $categories = Category::paginate(10); // Paginate with 10 items per page
        return view('admin.categories', compact('categories'));
    }

    public function create_Category()
    {
        return view('admin.category-create');
    }
    public function edit_Category($id)
    {
        $category = Category::findOrFail($id); // Retrieve the category by ID
        return view('admin.category-edit', compact('category')); // Pass the $category variable to the view
    }
    public function update_Category(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $category = Category::findOrFail($request->id);
        $category->name = $request->name;

        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if (File::exists(public_path('uploads/categories/' . $category->image))) {
                File::delete(public_path('uploads/categories/' . $category->image));
            }

            $image = $request->file('image');
            $fileName = time() . '.' . $image->getClientOriginalExtension(); // Correct method
            $image->move(public_path('uploads/categories'), $fileName); // Move the file
            $category->image = $fileName;
        }

        $category->save();

        return redirect()->route('admin.categories')->with('success', 'Category updated successfully!');
    }

    public function store_Category(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image_name' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $category = new Category();
        $category->name = $request->name;

        // Main image
        if ($request->hasFile('image_name')) {
            $image = $request->file('image_name');
            $fileName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/categories'), $fileName);
            $category->image = $fileName;
        }

        // Gallery images


        $category->save();

        return redirect()->route('admin.categories')->with('success', 'Category created successfully!');
    }
    public function delete_Category($id)
    {
        $category = Category::findOrFail($id);

        // Delete the image file if it exists
        if (File::exists(public_path('uploads/categories/' . $category->image))) {
            File::delete(public_path('uploads/categories/' . $category->image));
        }

        // Delete the category record
        $category->delete();

        return redirect()->route('admin.categories')->with('success', 'Category deleted successfully!');
    }
    
    public function products()
    {
        $products = Product::orderBy('created_at', 'desc')->paginate(10); // Paginate with 10 items per page
        return view('admin.products', compact('products'));
    }

    public function create_Product()
    {
        $categories = Category::all(); // Get all categories
        $brands = Brand::all(); // Get all brands
        return view('admin.product-create', compact('categories', 'brands'));
    }

    public function store_Product(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'slug' => 'required|string|max:255|unique:products,slug',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:còn hàng,hết hàng',
            'is_featured' => 'boolean',
            'processor_info' => 'nullable|string',
            'amount' => 'required|integer|min:1',
            'image_name' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $product = new Product();
        $product->name = $request->name;
        $product->description = $request->description;
        $product->slug = Str::slug($request->slug);
        $product->price = $request->price;
        $product->status = $request->status;
        $product->is_featured = $request->is_featured;
        $product->processor_info = $request->processor_info;
        $product->amount = $request->amount;

        // Main image
        if ($request->hasFile('image_name')) {
            $image = $request->file('image_name');
            $fileName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/products'), $fileName);
            $product->image_name = $fileName;
        }

        // Gallery images
        $galleryImages = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $galleryImage) {
                $galleryFileName = time() . '_' . uniqid() . '.' . $galleryImage->getClientOriginalExtension();
                $galleryImage->move(public_path('uploads/products/gallery'), $galleryFileName);
                $galleryImages[] = $galleryFileName;
            }
        }
        $product->gallery_images = !empty($galleryImages) ? json_encode($galleryImages) : null;

        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;

        $product->save();

        return redirect()->route('admin.products')->with('success', 'Product created successfully!');
    }

    public function edit_Product($id)
    {
        $product = Product::findOrFail($id); 
        $categories = Category::all(); 
        $brands = Brand::all(); 
        return view('admin.product-edit', compact('product', 'categories', 'brands'));
    }

    public function update_Product(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'slug' => 'required|string|max:255|unique:products,slug,' . $id,
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:còn hàng,hết hàng',
            'is_featured' => 'boolean',
            'processor_info' => 'nullable|string',
            'amount' => 'required|integer|min:1',
            'image_name' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $product = Product::findOrFail($id);
        $product->name = $request->name;
        $product->description = $request->description;
        $product->slug = Str::slug($request->slug);
        $product->price = $request->price;
        $product->status = $request->status;
        $product->is_featured = $request->is_featured;
        $product->processor_info = $request->processor_info;
        $product->amount = $request->amount;

        if ($request->hasFile('image_name')) {
            // Delete the old image if it exists
            if (File::exists(public_path('uploads/products/' . $product->image_name))) {
                File::delete(public_path('uploads/products/' . $product->image_name));
            }

            $image = $request->file('image_name');
            $fileName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/products'), $fileName);
            $product->image_name = $fileName;
        }

        // Gallery images
        $galleryImages = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $galleryImage) {
                $galleryFileName = time() . '_' . uniqid() . '.' . $galleryImage->getClientOriginalExtension();
                $galleryImage->move(public_path('uploads/products/gallery'), $galleryFileName);
                $galleryImages[] = $galleryFileName;
            }
            $product->gallery_images = json_encode($galleryImages);
        }
        

        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;

        if ($product->save()) {
            return redirect()->route('admin.products')->with('success', 'Product updated successfully!');
        } else {
            return redirect()->back()->with('error', 'Failed to update product!');
        }
    }
    public function delete_Product($id)
    {
        $product = Product::findOrFail($id);

        // Delete the image file if it exists
        if (File::exists(public_path('uploads/products/' . $product->image_name))) {
            File::delete(public_path('uploads/products/' . $product->image_name));
        }

        // Delete the product record
        $product->delete();

        return redirect()->route('admin.products')->with('success', 'Product deleted successfully!');
    }

    public function orders()
    {
        $orders = Order::with(['user', 'transaction'])->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.orders', compact('orders'));
    }

    public function orderDetails($id)
    {
        $order = Order::with(['user', 'transaction', 'orderDetails.product'])->findOrFail($id);
        return view('admin.order-details', compact('order'));
    }

    public function updateOrderStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->status = $request->status;
        $order->last_modified_by = Auth::id();
        
        if ($request->status === 'delivered') {
            $order->deliver_date = now();
        } elseif ($request->status === 'cancelled') {
            $order->cancel_date = now();
        }
        
        $order->save();
        
        return redirect()->back()->with('success', 'Order status updated successfully');
    }

    public function users()
    {
        $users = User::paginate(10);
        return view('admin.users', compact('users'));
    }

    public function userEdit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.user-edit', compact('user'));
    }

    public function userUpdate(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'nullable|min:8|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save();
        return redirect()->route('admin.users')->with('success', 'User updated successfully');
    }

    public function userDelete($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('admin.users')->with('success', 'User deleted successfully');
    }

    public function search(Request $request)
    {
        $query = $request->input('search');
        
        $orders = Order::where('id', 'LIKE', "%$query%")
            ->orWhereHas('user', function($q) use ($query) {
                $q->where('name', 'LIKE', "%$query%")
                    ->orWhere('email', 'LIKE', "%$query%");
            })
            ->orWhere('shipping_phone', 'LIKE', "%$query%")
            ->orWhere('shipping_address', 'LIKE', "%$query%")
            ->paginate(10);

        $products = Product::where('name', 'LIKE', "%$query%")
            ->orWhere('description', 'LIKE', "%$query%")
            ->orWhere('price', 'LIKE', "%$query%")
            ->paginate(10);

        return view('admin.search-results', compact('orders', 'products', 'query'));
    }
}

