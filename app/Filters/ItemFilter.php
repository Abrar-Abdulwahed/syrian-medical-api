<?php

namespace App\Filters;

use App\Models\ProviderService;

// Filter ProviderServices || Products
class ItemFilter extends ApplyFilter
{
    public function category($value)
    {
        if ($this->query->getModel() instanceof ProviderService) {
            return $this->query->whereRelation('service', 'category_id',  $value);
        }
    }

    public function title($value)
    {
        if ($this->query->getModel() instanceof ProviderService) {
            return $this->query->whereRelation('service', 'title_ar',  'LIKE', "%{$value}%")
                ->orWhereRelation('service', 'title_en',  'LIKE', "%{$value}%");
        } else {
            return $this->query->where('title_ar', 'LIKE', '%' . $value . '%')->orWhere('title_en', 'LIKE', '%' . $value . '%');
        }
    }
}
