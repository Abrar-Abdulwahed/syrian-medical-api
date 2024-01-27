<?php

namespace App\Http\Resources;

use App\Models\Product;
use App\Enums\OrderStatus;
use Illuminate\Http\Request;
use App\Models\ProviderService;
use App\Models\ProductReservation;
use App\Models\ServiceReservation;
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
        // FOR PATIENT || ADMIN
        $reservation = $this->reservationable;
        if ($reservation instanceof ProductReservation) {
            $item = new ProductReviewResource($reservation->product);
        } else if ($reservation instanceof ServiceReservation) {
            $item = new ServiceReviewResource($reservation->service);
        }

        return [
            'item' => $item,
            'appointment' => $this->when($reservation instanceof ServiceReservation, $this->reservationable->appointment_date . ' ' . $this->reservationable->appointment_time),
            'quantity' => $this->when($reservation instanceof ProductReservation, $this->reservationable->quantity),
            'location' => $this->location,
            'payment_method' => json_decode($this->payment_method),
            'status'      => $this->getStatusLabel($this->status),
            'rejection_reason' => $this->when(
                $this->relationLoaded('rejectionReason') && $this->status === OrderStatus::CANCELED->value,
                function () {
                    return $this->rejectionReason->rejection_reason;
                }
            ),
            'rejected_at' => $this->when(
                $this->relationLoaded('rejectionReason') && $this->status === OrderStatus::CANCELED->value,
                function () {
                    return $this->rejectionReason->rejected_at; // Replace 'rejected_at' with the actual attribute name
                }
            ),
            'ordered_at'  => $this->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}
