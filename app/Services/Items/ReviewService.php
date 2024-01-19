<?php

namespace App\Services\Items;

use App\Models\Product;
use App\Enums\OfferingType;
use App\Actions\ProductItem;
use App\Actions\ServiceItem;
use App\Models\ProviderService;
use App\Contracts\OfferingsInterface;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Resources\ProductListResource;
use App\Http\Resources\ServiceListResource;

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
            return $this->show(new ServiceItem, $id);
        } else if ($type === OfferingType::PRODUCT->value) {
            return $this->show(new ProductItem, $id);
        }
    }

    public function show(OfferingsInterface $itemInterface, $id)
    {
        return $itemInterface->show($id);
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
