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
        $attributes = $this->resource->getAttributes();

        $profile = null;

        if ($this->isPatient()) {
            $profile = new ProfileResource($this->whenLoaded('patientProfile'));
        }
        elseif ($this->isServiceProvider()) {
            $profile = new ProfileResource($this->whenLoaded('serviceProviderProfile'));
        }

        // Remove 'password' attribute from being returned
        unset($attributes['password']);

        return array_merge($attributes, [
            'profile' =>  $profile,

            //! donsn't work as chaining when()
            //$this->when('serviceProviderProfile', new ProfileResource($this->whenLoaded('serviceProviderProfile')))->when($this->isPatient(), new ProfileResource($this->whenLoaded('patientProfile'))),
        ]);
    }
}
