<?php

namespace App\Http\Resources;

use App\Enums\OfferingType;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $service = Service::find($this->service_id);
        $locale = app()->getLocale();
        return [
            'id'          => $this->id,
            'title'       => $service->{"title_" . $locale},
            'description' => $this->{"description_" . $locale},
            'category'    => $service->category->{"name_" . $locale},
            'thumbnail'   => $service->thumbnail,
            'type'        => getLocalizedEnumValue(OfferingType::SERVICE->value),
            'link'        => url()->current() . '/' . OfferingType::SERVICE->value . '/' . $this->id,
            'price'       => $this->price,
            'final_price' => $this->when($this->final_price, $this->final_price),
        ];
    }
}
