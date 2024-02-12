<?php

namespace App\Filters;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ApplyFilter
{
    protected $query;
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply(Builder $query)
    {
        $this->query = $query;
        foreach ($this->parameters() as $name => $value) {
            if (method_exists($this, $name)) {
                $this->$name($value);
            }
        }
        return $this->query;
    }

    public function parameters()
    {
        return $this->request->all();
    }
}
