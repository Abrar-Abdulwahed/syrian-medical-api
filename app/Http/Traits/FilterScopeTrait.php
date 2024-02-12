<?php

namespace App\Http\Traits;

use App\Filters\ApplyFilter;
use Illuminate\Database\Eloquent\Builder;

trait FilterScopeTrait
{
    public function ScopeFilter(Builder $builder, ApplyFilter $params)
    {
        return $params->apply($builder);
    }
}
