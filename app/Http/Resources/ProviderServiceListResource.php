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
            'title'          => $this->title,
            'thumbnail'      => $this->thumbnail,
            'type'           => OfferingType::SERVICE->value,
            'link'           => route('services.show', $this->id),
            'description'    => $this->pivot->description,
            'price'          => $this->pivot->price,
            'final_price'    => $this->when($this->pivot->discount > 0, $this->pivot->price - ($this->pivot->price * ($this->pivot->discount / 100))),
        ];
    }
}
