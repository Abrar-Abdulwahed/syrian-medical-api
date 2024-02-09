<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\PatientProfileResource;
use App\Http\Resources\ProviderProfileResource;
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
        $locale = app()->getLocale();
        $attributes = [
            "username" => $this->fullName,
            "link"  => route('admin.show.user', $this->id),
            "email" =>  $this->email,
            "picture" => $this->picture,
            "type" => $this->getTypeLabel($this->type, $locale),
            "activated"  =>  $this->activated,
            "joined_at" => $this->created_at->format('Y-m-d H:i:s'),
        ];

        $profile = null;

        if ($this->isPatient()) {
            $profile = new PatientProfileResource($this->whenLoaded('patientProfile'));
        } elseif ($this->isServiceProvider()) {
            $profile = new ProviderProfileResource($this->whenLoaded('serviceProviderProfile'));
        }

        return array_merge($attributes, [
            'profile' =>  $profile,
            'total_orders'      => $this->whenHas('total_orders_count'),
            'completed_orders'  => $this->whenHas('completed_orders_count'),
            'refused_orders'    => $this->whenHas('canceled_orders_count'),
        ]);
    }
}
