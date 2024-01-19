<?php

namespace App\Http\Resources;

use App\Models\User;
use App\Models\Admin;
use App\Models\Service;
use App\Enums\OfferingType;
use Illuminate\Http\Request;
use App\Http\Resources\AvailabilityResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $service = Service::find($this->service_id);
        // $user = User::find($this->provider_id);
        $attributes =  [
            'id'             => $this->id,
            'type'           => OfferingType::SERVICE->value,
            'title'          => $service->title,
            'thumbnail'      => $service->thumbnail,
            'description'    => $this->description,
            'availabilities' => AvailabilityResource::collection($this->availabilities),
            'discount'       => $this->discount,
            'price'          => $this->price,
            'final_price'    => $this->when($this->discount > 0, $this->price - ($this->price * ($this->discount / 100))),
        ];

        // show more info for provider and admin
        if ($request->user() instanceof Admin || $request->user()->id === $this->provider_id)
            $attributes = array_merge($attributes, [
                //TODO: reservations
                'orders'     => 0,
                'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
                'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
            ]);
        return $attributes;
    }
}
