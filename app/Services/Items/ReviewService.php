<?php

namespace App\Services\Items;

use App\Models\Admin;
use App\Models\Product;
use App\Enums\OrderStatus;
use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Models\ProviderService;
use App\Http\Traits\ApiResponseTrait;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Resources\Item\ProductReviewResource;
use App\Http\Resources\Item\ServiceReviewResource;

class ReviewService
{
    use ApiResponseTrait;
    public function getItemByType(Request $request)
    {
        $user = $request->user();
        $item = $request->item;

        // Show the total/completed/canceled orders ONLY for admin and owner provider
        if ($user instanceof Admin || $user->id === $item->provider_id) {
            $item->loadCount([
                'reservations as total_orders_count',
                'reservations as completed_orders_count' => function (Builder $query) {
                    $query->whereHas('morphReservation', function ($query) {
                        $query->whereIn('status', Reservation::COMPLETED_STATUSES);
                    });
                },
                'reservations as canceled_orders_count' => function (Builder $query) {
                    $query->whereHas('morphReservation', function ($query) {
                        $query->where('status', OrderStatus::CANCELED->value);
                    });
                },
            ]);
        }
        if ($item instanceof ProviderService) {
            $item->load('availabilities');
            return $this->returnJSON(new ServiceReviewResource($item), __('message.data_retrieved', ['item' => __('message.service')]));
        } else if ($item instanceof Product) {
            return $this->returnJSON(new ProductReviewResource($item), __('message.data_retrieved', ['item' => __('message.product')]));
        }
    }
}
