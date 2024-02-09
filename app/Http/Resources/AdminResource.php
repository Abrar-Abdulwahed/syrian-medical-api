<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $attributes = [
            "username"   => $this->username,
            "email"      =>  $this->email,
            "phone"      => $this->phone,
            "role"       => $this->role,
            "activated"  =>  $this->activated,
            "joined_at"  => $this->created_at->format('Y-m-d H:i:s'),
        ];

        return $attributes;
    }
}
