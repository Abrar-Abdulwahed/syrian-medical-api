<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $attributes = $this->resource->getAttributes();

        // Remove 'password' attribute from being returned
        unset($attributes['password']);

        return array_merge($attributes, [
            'role'       => $this->roles[0]->name,
        ]);
    }
}
