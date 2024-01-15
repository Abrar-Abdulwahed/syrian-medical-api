<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProviderProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $attributes =  $this->resource->getAttributes();

        return array_merge($attributes, [
            'services_count' => $this->whenCounted('services'),
            'user'           => new UserResource($this->whenLoaded('user')),
        ]);
    }
}
