<?php

namespace App\Http\Traits;

trait PaginateResponseTrait
{
    protected function paginateResponse($data)
    {
        $meta = [
            'path' => $data->path(),
            'current_page' => $data->currentPage(),
            'from' => $data->firstItem(),
            'to' => $data->lastItem(),
            'per_page' => $data->perPage(),
            'total' => $data->total(),
            'last_page' => $data->lastPage(),
        ];

        $links = [
            'first' => $data->url(1),
            'last' => $data->url($data->lastPage()),
            'prev' => $data->previousPageUrl(),
            'next' => $data->nextPageUrl(),
        ];
        return [$meta, $links];
    }
}