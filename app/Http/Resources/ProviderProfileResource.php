<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
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
        return  [
            'bank_name' => $this->bank_name,
            'iban_number' => $this->iban_number,
            'swift_code' =>  $this->swift_code,
            'evidence' => $this->evidence,
            'location' => [
                "latitude" => $this->latitude,
                "longitude" => $this->longitude,
            ],
        ];
    }
}
