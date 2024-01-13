<?php

namespace App\Http\Resources;

use App\Enums\UserType;
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
        $attributes = [
            "username" => $this->fullName,
            // "ip" => $this->ip,
            "email" =>  $this->email,
            "picture" => $this->picture,
            "type" => $this->type,
            "activated"  =>  $this->activated,
            "joined_at" => $this->created_at->format('Y-m-d H:i:s'),
        ];

        $profile = null;

        if ($this->isPatient()) {
            $profile = new ProfileResource($this->whenLoaded('patientProfile'));
        }
        elseif ($this->isServiceProvider()) {
            $profile = new ProfileResource($this->whenLoaded('serviceProviderProfile'));
        }

        return array_merge($attributes, [
            'profile' =>  $profile,
        ]);
    }
}
