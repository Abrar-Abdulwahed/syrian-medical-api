<?php

namespace App\Http\Resources\Item;

use App\Enums\OfferingType;
use Illuminate\Http\Request;
use App\Http\Resources\Item\AvailabilityResource;
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
        $service = $this->resource->service;
        $locale = app()->getLocale();
        $attributes =  [
            'id'                => $this->id,
            'type'              => getLocalizedEnumValue(OfferingType::SERVICE, $locale),
            'title'             => $service->{"title_" . $locale},
            'description'       => $this->{"description_" . $locale},
            'thumbnail'         => $service->thumbnail,
            'availabilities'    => AvailabilityResource::collection($this->whenLoaded('availabilities')),
            'discount'          => $this->discount,
            'price'             => $this->price,
            'final_price'       => $this->when($this->final_price, $this->final_price),
            'total_orders'      => $this->whenHas('total_orders_count'),
            'completed_orders'  => $this->whenHas('completed_orders_count'),
            'refused_orders'    => $this->whenHas('canceled_orders_count'),
            'created_at'        => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at'        => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
        return $attributes;
    }
}
