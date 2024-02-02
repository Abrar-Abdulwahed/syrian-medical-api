<?php

namespace App\Http\Resources;

use App\Enums\OrderStatus;
use Illuminate\Http\Request;
use App\Models\ProductReservation;
use App\Models\ServiceReservation;
use Illuminate\Http\Resources\Json\JsonResource;

class SalesListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // ADMIN
        $reservation = $this->reservationable;
        $item = null;
        if ($reservation && $reservation instanceof ProductReservation) {
            $item = new ProductReviewResource($reservation->product);
        } else if ($reservation && $reservation instanceof ServiceReservation) {
            $item = new ServiceReviewResource($reservation->service);
        }

        return [
            'order_id' => $item->id,
            'title'    => $item->title ?? $item->service->title,
            'status'   => $this->getStatusLabel($this->status),
            'paid_at'  => $this->updated_at->format('Y-m-d H:i:s'),
            'price'    => $this->price,
        ];
    }
}
