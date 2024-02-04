<?php

namespace App\Http\Controllers\Admin\ItemManagement;

use App\Models\User;
use App\Models\Product;
use App\Services\Items\ProductService;
use App\Http\Controllers\Admin\BaseAdminController;
use App\Http\Requests\ServiceProvider\ProductStoreRequest;
use App\Http\Requests\ServiceProvider\ProductUpdateRequest;

class ProductController extends BaseAdminController
{
    public function __construct(protected ProductService $productService)
    {
        parent::__construct();
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
