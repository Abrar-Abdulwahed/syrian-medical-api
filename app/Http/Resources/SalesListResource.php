<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Models\ProductReservation;
use App\Models\ServiceReservation;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Item\ProductReviewResource;
use App\Http\Resources\Item\ServiceReviewResource;

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
        $locale = app()->getLocale();
        if ($reservation && $reservation instanceof ProductReservation) {
            $item = new ProductReviewResource($reservation->product);
        } else if ($reservation && $reservation instanceof ServiceReservation) {
            $item = new ServiceReviewResource($reservation->service);
        }

        return [
            'id'       => $this->id,
            'title'    => $item->{"title_" . $locale} ?? $item->service->{"title_" . $locale},
            'status'   => $this->getStatusLabel($this->status, $locale),
            'paid_at'  => $this->updated_at->format('Y-m-d H:i:s'),
            'price'    => $this->price,
        ];
    }
}
