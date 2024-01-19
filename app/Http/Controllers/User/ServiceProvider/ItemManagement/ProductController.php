<?php

namespace App\Http\Controllers\User\ServiceProvider\ItemManagement;

use App\Models\Product;
use App\Http\Controllers\Controller;
use App\Services\Items\ProductService;
use App\Http\Requests\ServiceProvider\ProductStoreRequest;
use App\Http\Requests\ServiceProvider\ProductUpdateRequest;

class ProductController extends Controller
{
    public function __construct(protected ProductService $productService)
    {
        $this->middleware(['auth:sanctum', 'verified', 'activated']);
        $this->authorizeResource(Product::class, 'product');
    }

    public function store(ProductStoreRequest $request)
    {
        return $this->productService->store($request->user(), $request->validated());
    }

    public function update(ProductUpdateRequest $request, Product $product)
    {
        return $this->productService->update($request->validated(), $product);
    }

    public function destroy(Product $product)
    {
        return $this->productService->destroy($product);
    }
}
