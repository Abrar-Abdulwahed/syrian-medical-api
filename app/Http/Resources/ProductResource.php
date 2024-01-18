<?php

namespace App\Http\Resources;

use App\Enums\OfferingType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        dd($request->user());
        if ($request->user()->id === $this->user_id)
            $link = route('products.show', $this->id);
        //TODO: modify to give route for admin to review
        if ($request->user()->id)
            $link = route('users.offerings.show', [OfferingType::PRODUCT->value, $this->id]);

        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'thumbnail'   => $this->thumbnail,
            'type'        => OfferingType::PRODUCT->value,
            'link'        => $link,
            'discount'    => $this->discount,
            'price'       => $this->price,
            'final_price' => $this->when($this->discount > 0, $this->price - ($this->price * ($this->discount / 100))),
            'created_at'  => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at'  => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
