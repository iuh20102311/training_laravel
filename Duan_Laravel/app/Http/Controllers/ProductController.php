<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    protected $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function index(Request $request)
    {
        $result = $this->productRepository->all($request, $request->input('perPage', 10));
        $products = $result['products'];
        $filters = $result['filters'];

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
        $product = $this->productRepository->createProduct($request->validated());
        return redirect()->route('products.index')->with('success', trans('products.product_created_success'));
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $this->productRepository->updateProduct($product, $request->validated());
        return redirect()->route('products.index')->with('success', trans('products.product_updated_success'));
    }

    public function destroy(Product $product)
    {
        $this->productRepository->deleteProduct($product);

        if (request()->ajax()) {
            $filters = [
                'name' => request('name'),
                'status' => request('status'),
                'min_price' => request('min_price'),
                'max_price' => request('max_price'),
            ];

            $totalFiltered = $this->productRepository->getTotalFiltered($filters);

            return response()->json([
                'message' => trans('products.product_deleted_success'),
                'total' => $totalFiltered,
            ], 200);
        }

        return redirect()->route('products.index')->with('success', trans('products.product_deleted_success'));
    }
}
