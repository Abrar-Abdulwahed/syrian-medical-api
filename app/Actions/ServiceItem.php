<?php

namespace App\Actions;

use App\Models\ProviderService;
use App\Contracts\OfferingsInterface;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Resources\ServiceReviewResource;

class ServiceItem implements OfferingsInterface
{
    use ApiResponseTrait;
    public function show(string $id)
    {
        $providerService = ProviderService::findOrFail($id);
        return $this->returnJSON(new ServiceReviewResource($providerService), 'Data retrieved successfully');
    }
}
