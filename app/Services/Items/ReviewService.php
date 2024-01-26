<?php

namespace App\Services\Items;

use App\Models\Admin;
use App\Models\Product;
use App\Enums\OrderStatus;
use App\Enums\OfferingType;
use Illuminate\Http\Request;
use App\Models\ProviderService;
use App\Http\Traits\ApiResponseTrait;
use Illuminate\Database\Eloquent\Builder;
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

    public function getItemByType(Request $request)
    {
        $user = $request->user();
        $item = $request->item;

        // Show the total/completed/canceled orders ONLY for admin and owner provider
        if ($user instanceof Admin || $user->id === $item->provider_id) {
            $item->loadCount([
                'reservations as total_orders_count',
                'reservations as completed_orders_count' => function (Builder $query) {
                    $query->whereRelation('morphReservation', 'status', OrderStatus::COMPLETED->value);
                },
                'reservations as canceled_orders_count' => function (Builder $query) {
                    $query->whereRelation('morphReservation', 'status', OrderStatus::CANCELED->value);
                },
            ]);
        }
        if ($item instanceof ProviderService) {
            $item->load('availabilities');
            return $this->returnJSON(new ServiceReviewResource($item), 'Data retrieved successfully');
        } else if ($item instanceof Product) {
            return $this->returnJSON(new ProductReviewResource($item), 'Data retrieved successfully');
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
