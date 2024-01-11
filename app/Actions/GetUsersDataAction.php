<?php

namespace App\Actions;

use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Traits\PaginateResponseTrait;

class GetUsersDataAction
{
    use ApiResponseTrait, PaginateResponseTrait;
    public function __invoke(Request $request, array $withRelations, $query){
        $pageSize = $request->per_page ?? 10;
        $users = $query->with($withRelations)->paginate($pageSize);
        [$meta, $links] = $this->paginateResponse($users);
        return $this->returnAllDataJSON(UserResource::collection($users), $meta, $links, 'Data retrieved successfully');
    }
}