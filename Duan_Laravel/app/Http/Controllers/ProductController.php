<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        $filters = [
            'name' => $request->name,
            'status' => $request->status,
            'price' => $request->price,
            'min_price' => $request->min_price,
            'max_price' => $request->max_price,
        ];

        if ($filters['name']) {
            $query->where('name', 'like', $filters['name'] . '%');
        }

        if ($filters['min_price']) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($filters['max_price']) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->filled('status')) {
            $query->where('status', $filters['status']);
        }

        $perPage = $request->input('perPage', 10);

        $products = $query->orderByDesc('created_at')->paginate($perPage)->appends($filters);

        return view('products.index', compact('products', 'filters'));        
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(StoreProductRequest $request)
    {
        $validatedData = $request->validated();

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $firstChar = strtoupper(substr($validatedData['name'], 0, 1));
            $randomNum = str_pad(mt_rand(0, 9999999999), 10, '0', STR_PAD_LEFT);
            $fileName = $firstChar . $randomNum . '.' . $extension;
    
            $imagePath = $file->storeAs('products', $fileName, 'public');
            $validatedData['image'] = $imagePath;
        }

        Product::create($validatedData);

        return redirect()->route('products.index')->with('success', 'Sản phẩm đã được tạo thành công.');
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $validatedData = $request->validated();

        $validatedData['status'] = ucfirst(strtolower($validatedData['status']));

        // Thêm ảnh đổi tên lại 
        if ($request->hasFile('image')) {
            // Xóa hình ảnh cũ nếu có
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $firstChar = strtoupper(substr($validatedData['name'], 0, 1));
            $randomNum = str_pad(mt_rand(0, 9999999999), 10, '0', STR_PAD_LEFT);
            $fileName = $firstChar . $randomNum . '.' . $extension;
    
            $imagePath = $file->storeAs('products', $fileName, 'public');
            $validatedData['image'] = $imagePath;
        }

        $product->update($validatedData);

        return redirect()->route('products.index')->with('success', 'Sản phẩm đã được cập nhật thành công.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Sản phẩm đã được xóa thành công.');
    }
}