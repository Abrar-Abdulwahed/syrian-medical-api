<?php

namespace App\Http\Resources;

use App\Enums\OfferingType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProviderServiceListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'title'          => getLocalizedValue($this, 'title'),
            'description'    => getLocalizedValue($this->pivot, 'description'),
            'thumbnail'      => $this->thumbnail,
            'type'           => getLocalizedEnumValue(OfferingType::SERVICE->value),
            'link'           => url()->current() . '/' . OfferingType::SERVICE->value . '/' . $this->id,
            'price'          => $this->pivot->price,
            'final_price'    => $this->when($this->pivot->final_price, $this->pivot->final_price),
        ];
    }
}
