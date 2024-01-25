<?php

namespace App\Http\Resources;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\ProviderService;
use App\Models\ProductReservation;
use App\Models\ServiceReservation;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $instance = $this->resource;
        if ($instance instanceof ProductReservation) {
            $item = new ProductReviewResource($instance->product);
        } elseif ($instance instanceof ServiceReservation) {
            $item = new ServiceReviewResource($instance->service);
        }

        return [
            'Item' => $item,
            'appointment' => $this->when(
                $instance instanceof ServiceReservation,
                $instance->appointment_date . ' ' . $instance->appointment_time
            ),
            'quantity' => $this->when($instance instanceof ProductReservation, $instance->quantity),
            'location' => $instance->morphReservation->location,
            'payment_method' => json_decode($instance->morphReservation->payment_method),
        ];
    }
}
