<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Resources\Json\JsonResource;

class PermissionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $locale = app()->getLocale();
        return [
            'id'            => $this->id,
            'description'   => $this->resource->{"description_" . $locale},
            'hasPermission' => $this->hasPermission,
        ];
    }
}
