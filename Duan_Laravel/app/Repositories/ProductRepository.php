<?php

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class ProductRepository implements ProductRepositoryInterface
{
    public function all(Request $request, int $perPage)
    {
        $query = Product::query();

        $filters = [
            'name' => $request->name,
            'min_price' => $request->min_price,
            'max_price' => $request->max_price,
            'status' => $request->status,
        ];

        if (!empty($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }

        if (!empty($filters['min_price']) && is_numeric($filters['min_price']) && $filters['min_price'] >= 0) {
            $query->where('price', '>=', $filters['min_price']);
        }

        if (!empty($filters['max_price']) && is_numeric($filters['max_price']) && $filters['max_price'] >= 0) {
            $query->where('price', '<=', $filters['max_price']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return [
            'query' => $query,
            'filters' => $filters,
            'products' => $query->orderByDesc('created_at')->paginate($perPage)->appends($filters)
        ];
    }

    public function createProduct(array $data): Product
    {
        $productId = $this->generateProductId($data['name']);
        $data['product_id'] = $productId;
        if (isset($data['image'])) {
            $data['image'] = $this->uploadImage($data['image'], $productId);
        }

        return Product::create($data);
    }

    public function updateProduct(Product $product, array $data): bool
    {
        if (isset($data['delete_image']) && $data['delete_image'] == '1') {
            $this->deleteImage($product->image);
            $data['image'] = null;
        } elseif (isset($data['image'])) {
            $this->deleteImage($product->image);
            $data['image'] = $this->uploadImage($data['image'], $product->product_id);
        }

        return $product->update($data);
    }

    public function deleteProduct(Product $product): bool
    {
        if ($product->image) {
            $this->deleteImage($product->image);
        }
        return $product->delete();
    }

    public function generateProductId(string $name): string
    {
        $firstChar = Str::upper(Str::substr($name, 0, 1));
        $latestProduct = Product::where('product_id', 'like', $firstChar . '%')
            ->orderBy('product_id', 'desc')
            ->first();

        if ($latestProduct) {
            $latestNumber = (int) Str::substr($latestProduct->product_id, 1);
            $newNumber = $latestNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $firstChar . str_pad($newNumber, 10, '0', STR_PAD_LEFT);
    }

    public function getTotalFiltered(array $filters): int
    {
        $query = Product::query();

        if (!empty($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }

        if (!empty($filters['min_price']) && is_numeric($filters['min_price']) && $filters['min_price'] >= 0) {
            $query->where('price', '>=', $filters['min_price']);
        }

        if (!empty($filters['max_price']) && is_numeric($filters['max_price']) && $filters['max_price'] >= 0) {
            $query->where('price', '<=', $filters['max_price']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->count();
    }

    public function uploadImage($file, $productId)
    {
        $extension = $file->getClientOriginalExtension();
        $fileName = $productId . '.' . $extension;

        return $file->storeAs('products', $fileName, 'public');
    }

    public function deleteImage($imagePath)
    {
        if ($imagePath && Storage::disk('public')->exists($imagePath)) {
            Storage::disk('public')->delete($imagePath);
        }
    }
}
