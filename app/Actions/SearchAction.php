<?php

namespace App\Actions;

use Illuminate\Http\Request;

class SearchAction
{
    public function searchAction($query, $searchTerm)
    {
        $query->when($searchTerm, function ($query) use ($searchTerm) {
            $query->where(function ($query) use ($searchTerm) {
                $query->search($searchTerm);
            });
        });
        return $query;
    }
}
