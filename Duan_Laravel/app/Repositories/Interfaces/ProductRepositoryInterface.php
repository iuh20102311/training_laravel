<?php

namespace App\Repositories\Interfaces;

use App\Models\Product;
use Illuminate\Http\Request;

interface ProductRepositoryInterface
{
    public function all(Request $request, int $perPage);
    public function createProduct(array $data): Product;
    public function updateProduct(Product $product, array $data): bool;
    public function deleteProduct(Product $product): bool;
    public function generateProductId(string $name): string;
    public function getTotalFiltered(array $filters): int;
    public function uploadImage($file, $productId);
    public function deleteImage($imagePath);
}