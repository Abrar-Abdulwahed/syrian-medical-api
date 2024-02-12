<?php

namespace App\Filters;

use App\Models\ProviderService;

class ItemFilter extends ApplyFilter
{
    public function category($value)
    {
        if ($this->query->getModel() instanceof ProviderService) {
            return $this->query->whereHas('service', function ($query) use ($value) {
                $query->where('category_id', $value);
            });
        }
        return null;
    }

    public function title($value)
    {
        if ($this->query->getModel() instanceof ProviderService) {
            return $this->query->whereHas('service', function ($query) use ($value) {
                $query->where('title_ar', 'LIKE', "%{$value}%")
                    ->orWhere('title_en', 'LIKE', "%{$value}%");
            });
        } else {
            return $this->query->where('title_ar', 'LIKE', '%' . $value . '%')->orWhere('title_en', 'LIKE', '%' . $value . '%');
        }
    }
}
