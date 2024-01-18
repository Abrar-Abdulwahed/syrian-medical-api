<?php

namespace App\Http\Resources;

use App\Enums\OfferingType;
use App\Models\User;
use App\Models\Service;
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
        $service = Service::find($this->service_id);

        return [
            'id'          => $this->id,
            'title'       => $service->title,
            'thumbnail'   => $service->thumbnail,
            'type'        => OfferingType::SERVICE->value,
            'link'        => route('users.services.show', $service->id),
            'description' => $this->description,
            'price'       => $this->price,
            'final_price' => $this->when($this->discount > 0, $this->price - ($this->price * ($this->discount / 100))),
        ];
    }
}