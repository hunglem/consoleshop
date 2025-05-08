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
use App\Models\Brand;
use Itervention\Image\Facades\Image;




class AdminController extends Controller
{
    public function index()
    {
        return view('admin.index');
    }
    
    public function brands()
    {
        $brands = Brand::paginate(10); // Paginate with 10 items per page
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
            if (File::exists(public_path('uploads/brands/' . $brand->image))) {
                File::delete(public_path('uploads/brands/' . $brand->image));
            }

            $image = $request->file('image');
            $fileName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/brands'), $fileName);
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


            $image = $request->file('image');
            $fileName = $image->file('image')->extendsion();
            $fileExtension = $request->getClientOriginalExtension();
            $image->move(public_path('uploads/brands'), $fileName);
            $this->GenarateThumbnailImage($fileName, $image);
            $brand->image = $fileName;
        

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

}