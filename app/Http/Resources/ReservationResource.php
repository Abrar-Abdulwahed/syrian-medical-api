<?php

namespace App\Http\Resources;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\ProviderService;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $item = $this->reservationable;
        if ($item instanceof Product)
            $resource = new ProductReviewResource($this->whenLoaded('reservationable'));
        else if ($item instanceof ProviderService)
            $resource = new ServiceReviewResource($this->whenLoaded('reservationable'));

        return [
            'Item' => $resource,
            'appointment' => $this->when($item instanceof ProviderService, $this->appointment_date . ' ' . $this->appointment_time),
            'location' => $this->location,
            'payment_method' => json_decode($this->payment_method),

        ];
    }
}
