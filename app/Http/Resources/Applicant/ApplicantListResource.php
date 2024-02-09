<?php

namespace App\Http\Resources\Applicant;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApplicantListResource extends JsonResource
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
            "link"  => route('admin.show.applicant', $this->id),
            "email" =>  $this->email,
            "picture" => $this->picture,
        ];
        return $attributes;
    }
}
