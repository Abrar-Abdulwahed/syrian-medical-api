<?php

namespace App\Services\Items;

use App\Models\Product;
use App\Enums\OfferingType;
use App\Models\ProviderService;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Resources\ProductListResource;
use App\Http\Resources\ServiceListResource;
use App\Http\Resources\ProductReviewResource;
use App\Http\Resources\ServiceReviewResource;

class ReviewService
{
    use ApiResponseTrait;
    public function getAllItems()
    {
        $products = Product::with('provider')->get();
        $services = ProviderService::get();
        $result =   ProductListResource::collection($products)->merge(ServiceListResource::collection($services));
        return $this->returnJSON($result, 'Data retrieved successfully');
    }

    public function getItemByType(string $id, string $type)
    {
        if ($type === OfferingType::SERVICE->value) {
            $providerService = ProviderService::findOrFail($id);
            return $this->returnJSON(new ServiceReviewResource($providerService), 'Data retrieved successfully');
        } else if ($type === OfferingType::PRODUCT->value) {
            $product = Product::findOrFail($id);
            return $this->returnJSON(new ProductReviewResource($product), 'Data retrieved successfully');
        }
    }

    public function getItemsByType(string $type)
    {
        if ($type === OfferingType::SERVICE->value) {
            $services = ProviderService::get();
            return $this->returnJSON(ServiceListResource::collection($services), 'Data retrieved successfully');
        } else if ($type === OfferingType::PRODUCT->value) {
            $products = Product::with('provider')->get();
            return $this->returnJSON(ProductListResource::collection($products), 'Data retrieved successfully');
        }
    }
}
