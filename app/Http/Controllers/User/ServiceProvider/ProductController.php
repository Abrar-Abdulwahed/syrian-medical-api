<?php

namespace App\Http\Controllers\User\ServiceProvider;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Http\Requests\ServiceProvider\ProductStoreRequest;
use App\Http\Requests\ServiceProvider\ProductUpdateRequest;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'verified', 'activated']);
        $this->authorizeResource(Product::class, 'product');
    }

    public function index(Request $request)
    {
        $pageSize = $request->per_page ?? 10;
        $products = $request->user()->products()->paginate($pageSize);
        [$meta, $links] = $this->paginateResponse($products);
        return $this->returnAllDataJSON(ProductResource::collection($products), $meta, $links, 'Data retrieved successfully');
    }

    public function store(ProductStoreRequest $request)
    {
        $product = $request->user()->products()->create($request->validated());
        if($request->hasFile('thumbnail')){
            $fileName = $this->uploadFile($request->file('thumbnail'), $product->attachment_path);
        }
        $product->update(['thumbnail' => $fileName]);
        return $this->returnSuccess('Product added successfully');
    }

    public function show(Product $product)
    {
        return $this->returnJSON(new ProductResource($product), 'Product data retrieved successfully');
    }

    public function update(ProductUpdateRequest $request, Product $product)
    {
        $product->update($request->validated());
        return $this->returnSuccess('Product data updated successfully');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return $this->returnSuccess('Product has been deleted successfully');
    }
}
