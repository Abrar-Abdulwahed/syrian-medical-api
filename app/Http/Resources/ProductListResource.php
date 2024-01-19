<?php

namespace App\Http\Resources;

use App\Models\User;
use App\Models\Admin;
use App\Enums\OfferingType;
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
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'thumbnail'   => $this->thumbnail,
            'type'        => OfferingType::PRODUCT->value,
            'link'        => url()->current() . '/' . OfferingType::PRODUCT->value . '/' . $this->id,
            'discount'    => $this->discount,
            'price'       => $this->price,
            'final_price' => $this->when($this->discount > 0, $this->price - ($this->price * ($this->discount / 100))),
        ];
    }
}
