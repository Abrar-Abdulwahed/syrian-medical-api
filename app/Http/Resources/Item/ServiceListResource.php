<?php

namespace App\Http\Resources\Item;

use App\Enums\OfferingType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $service = $this->service;
        $locale = app()->getLocale();
        return [
            'id'          => $this->id,
            'title'       => $service->{"title_" . $locale},
            'description' => $this->{"description_" . $locale},
            'thumbnail'   => $service->thumbnail,
            'type'        => getLocalizedEnumValue(OfferingType::SERVICE, $locale),
            'link'        => url()->current() . '/' . OfferingType::SERVICE->value . '/' . $this->id,
            'price'       => $this->price,
            'final_price' => $this->when($this->final_price, $this->final_price),
        ];
    }
}
