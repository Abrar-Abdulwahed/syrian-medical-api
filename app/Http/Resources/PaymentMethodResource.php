<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentMethodResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'card_type'           => ucfirst($this->resource['card_type']),
            'card_logo'           => DB::table('payment_methods')->where('name_en', $this->resource['card_type'])->first()->logo,
            'cardholder_name'     => $this->resource['cardholder_name'],
            'partial_card_number' => '**** **** **** ' . substr($this->resource['card_number'], -4),
            'expiration_date'     => $this->resource['expiration_month'] . '/' . $this->resource['expiration_year'],
        ];
    }
}
