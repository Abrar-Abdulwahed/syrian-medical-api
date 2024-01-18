<?php

namespace App\Actions;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\ProviderService;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ServiceListResource;

class GetAllItemsAction
{
    use ApiResponseTrait;
    public function getData()
    {
        $products = Product::with('provider')->get();
        $services = ProviderService::get();
        $result =   ProductResource::collection($products)->merge(ServiceListResource::collection($services));
        return $this->returnJSON($result, 'Data retrieved successfully');
    }
}
