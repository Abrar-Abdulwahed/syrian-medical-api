<?php

namespace App\Services;

use App\Models\Product;
use App\Enums\OfferingType;
use App\Actions\ProductItem;
use App\Actions\ServiceItem;
use App\Models\ProviderService;
use App\Contracts\OfferingsInterface;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ServiceListResource;

class OfferingsService
{
    use ApiResponseTrait;
    public function getAllItems()
    {
        $products = Product::with('provider')->get();
        $services = ProviderService::get();
        $result =   ProductResource::collection($products)->merge(ServiceListResource::collection($services));
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
            return $this->returnJSON(ProductResource::collection($products), 'Data retrieved successfully');
        }
    }
}
