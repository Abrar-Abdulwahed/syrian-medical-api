<?php

namespace App\Http\Controllers\Admin\ItemManagement;

use App\Models\User;
use App\Models\Product;
use App\Http\Controllers\Controller;
use App\Services\Items\ProductService;
use App\Http\Requests\ServiceProvider\ProductStoreRequest;
use App\Http\Requests\ServiceProvider\ProductUpdateRequest;

class ProductController extends Controller
{
    public function __construct(protected ProductService $productService)
    {
        $this->middleware(['auth:sanctum', 'activated', 'verified', 'is-admin']);
    }
    public function store(ProductStoreRequest $request)
    {
        $user = User::find($request->provider_id);
        return $this->productService->store($user, $request->validated());
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
