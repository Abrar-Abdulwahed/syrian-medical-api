<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\ProfileResource;
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

        // Include role in the response
        $response = array_merge($attributes, [
            'role' => $this->roles[0]->name,
        ]);

        return $response;
    }
}
