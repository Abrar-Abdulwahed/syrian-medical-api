<?php

namespace App\Filters;

class UserFilter extends ApplyFilter
{
    public function name($value)
    {
        return $this->query
            ->where('firstname', 'LIKE', "%{$value}%")
            ->orWhere('lastname', 'LIKE', "%{$value}%")
            ->orWhereRaw("CONCAT(firstname, ' ', lastname) LIKE ?", ["%{$value}%"]);
    }
}
