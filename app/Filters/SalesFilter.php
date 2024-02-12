<?php

namespace App\Filters;

use Carbon\Carbon;

class SalesFilter extends ApplyFilter
{
    public function month($value)
    {
        return $this->query->whereMonth('updated_at', $value);
    }

    public function year($value)
    {
        return $this->query->whereYear('updated_at', $value);
    }

    public function provider($value)
    {
        return $this->query->where('provider_id', $value);
    }

    public function paid_at($value)
    {
        $searchDateFormatted = Carbon::createFromFormat('m/d/Y', $value)->format('Y-m-d');
        return $this->query->where(function ($query) use ($searchDateFormatted) {
            $query->whereDate('updated_at', $searchDateFormatted);
        });
    }
}
