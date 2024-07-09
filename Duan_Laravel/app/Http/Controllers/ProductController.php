<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }

        if (!empty($filters['min_price']) && is_numeric($filters['min_price']) && $filters['min_price'] >= 0) {
            $query->where('price', '>=', $filters['min_price']);
        }

        if (!empty($filters['max_price']) && is_numeric($filters['max_price']) && $filters['max_price'] >= 0) {
            $query->where('price', '<=', $filters['max_price']);
        }

        if ($request->filled('status')) {
            $query->where('status', $filters['status']);
        }

        $perPage = $request->input('perPage', 10);

        $products = $query->orderByDesc('created_at')->paginate($perPage)->appends($filters);

        if ($request->ajax()) {
            return view('products.index', compact('products', 'filters'))
                ->fragment('products-list');
        }

        return view('products.index', compact('products', 'filters'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(StoreProductRequest $request)
    {
        $validatedData = $request->validated();

        // Tạo product_id
        $firstChar = Str::upper(Str::substr($validatedData['name'], 0, 1));
        $latestProduct = Product::where('product_id', 'like', $firstChar . '%')
            ->orderBy('product_id', 'desc')
            ->first();

        if ($latestProduct) {
            $latestNumber = (int) Str::substr($latestProduct->product_id, 1);
            $newNumber = $latestNumber + 1;
        } else {
            $newNumber = 1;
        }

        $validatedData['product_id'] = $firstChar . str_pad($newNumber, 10, '0', STR_PAD_LEFT);

        // Xử lý upload ảnh
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $fileName = $validatedData['product_id'] . '.' . $extension;

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

        // Xử lý xóa ảnh
        if ($request->has('delete_image') && $request->delete_image == '1') {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
                $validatedData['image'] = null; // Đặt giá trị trường image thành null
            }
        }

        // Xử lý upload ảnh mới
        elseif ($request->hasFile('image')) {
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
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        if (request()->ajax()) {
            $query = Product::query();

            $filters = [
                'name' => request('name'),
                'status' => request('status'),
                'min_price' => request('min_price'),
                'max_price' => request('max_price'),
            ];

            if ($filters['name']) {
                $query->where('name', 'like', '%' . $filters['name'] . '%');
            }

            if (!empty($filters['min_price']) && is_numeric($filters['min_price']) && $filters['min_price'] >= 0) {
                $query->where('price', '>=', $filters['min_price']);
            }

            if (!empty($filters['max_price']) && is_numeric($filters['max_price']) && $filters['max_price'] >= 0) {
                $query->where('price', '<=', $filters['max_price']);
            }

            if (request()->filled('status')) {
                $query->where('status', $filters['status']);
            }

            $totalFiltered = $query->count();

            return response()->json([
                'message' => 'Sản phẩm đã được xóa thành công.',
                'total' => $totalFiltered,
            ], 200);
        }

        return redirect()->route('products.index')->with('success', 'Sản phẩm đã được xóa thành công.');
    }

}
