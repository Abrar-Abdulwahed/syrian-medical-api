<?php

namespace App\Http\Controllers\User\ServiceProvider;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Items\ProductService;
use App\Http\Resources\ProductListResource;
use App\Http\Resources\ProductReviewResource;
use App\Http\Requests\ServiceProvider\ProductStoreRequest;
use App\Http\Requests\ServiceProvider\ProductUpdateRequest;

class ProductController extends Controller
{
    public function __construct(protected ProductService $productService)
    {
        $this->middleware(['auth:sanctum', 'verified', 'activated']);
        $this->authorizeResource(Product::class, 'product');
    }

    // public function index(Request $request)
    // {
    //     $pageSize = $request->per_page ?? 10;
    //     $products = $request->user()->products()->paginate($pageSize);
    //     [$meta, $links] = $this->paginateResponse($products);
    //     return $this->returnAllDataJSON(ProductListResource::collection($products), $meta, $links, 'Data retrieved successfully');
    // }
    // public function show(Product $product)
    // {
    //     return $this->returnJSON(new ProductReviewResource($product), 'Product data retrieved successfully');
    // }

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
