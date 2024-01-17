<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
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
            'title'       => $this->title,
            'thumbnail'   => $this->thumbnail,
            'description' => $this->pivot->description,
            'discount'    => $this->pivot->discount,
            'price'       => $this->pivot->price,
            'final_price' => $this->when($this->pivot->discount > 0, $this->pivot->price - ($this->pivot->price * ($this->pivot->discount / 100))),
            'created_at'  => $this->pivot->created_at?->format('Y-m-d H:i:s'),
            'updated_at'  => $this->pivot->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
