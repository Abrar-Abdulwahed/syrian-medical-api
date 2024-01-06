<?php

namespace App\Actions;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Traits\ApiResponseTrait;

class GetUsersDataAction
{
    use ApiResponseTrait;
    public function __invoke(Request $request, array $withRelations, $userType = null){
        $pageSize = $request->page_size ?? 10;
        $query = User::query();

        if ($userType) {
            $query->where('type', $userType);
        }
        $users = $query->with($withRelations)->paginate($pageSize);

        $meta = [
            'path'  => $users->path(),
            'current_page' => $users->currentPage(),
            'from' => $users->firstItem(),
            'to' => $users->lastItem(),
            'per_page' => $users->perPage(),
            'total' => $users->total(),
            'last_page' => $users->lastPage(),
        ];

        $links = [
            'first' => $users->url(1),
            'last' => $users->url($users->lastPage()),
            'prev' => $users->previousPageUrl(),
            'next' => $users->nextPageUrl(),
        ];

        return $this->returnAllDataJSON(UserResource::collection($users), $meta, $links, 'Data retrieve successfully');
    }
}