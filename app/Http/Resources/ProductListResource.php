<?php

namespace App\Http\Resources;

use App\Enums\OfferingType;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $locale = app()->getLocale();
        return [
            'id'          => $this->id,
            'title'       => $this->{"title_" . $locale},
            'description' => $this->{"description_" . $locale},
            'thumbnail'   => $this->thumbnail,
            'type'        => getLocalizedEnumValue(OfferingType::PRODUCT->value),
            'link'        => url()->current() . '/' . OfferingType::PRODUCT->value . '/' . $this->id,
            'discount'    => $this->discount,
            'price'       => $this->price,
            'final_price' => $this->when($this->final_price, $this->final_price),
        ];
    }
}
