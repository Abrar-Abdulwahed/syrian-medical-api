<?php

namespace App\Http\Resources\Applicant;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApplicantReviewResource extends JsonResource
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
            'bank_name' => $this->serviceProviderProfile->bank_name,
            'iban_number' => $this->serviceProviderProfile->iban_number,
            'swift_code' => $this->serviceProviderProfile->swift_code,
            'evidence' => $this->serviceProviderProfile->evidence,
            "joined_at" => $this->created_at->format('Y-m-d H:i:s'),
        ];
        return $attributes;
    }
}
