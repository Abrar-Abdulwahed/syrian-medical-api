<?php

namespace App\Filters;

class SupervisorFilter extends ApplyFilter
{
    public function name($value)
    {
        return $this->query->where('username', 'LIKE', "%{$value}%");
    }
}
