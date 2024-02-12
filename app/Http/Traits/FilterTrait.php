<?php

namespace App\Http\Traits;

use App\Filters\ApplyFilter;
use Illuminate\Database\Eloquent\Builder;

trait FilterTrait
{
    public function ScopeFilter(Builder $builder, ApplyFilter $parameters)
    {
        return $parameters->apply($builder);
    }
}
