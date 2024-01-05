<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Database\Eloquent\Builder;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $pageSize = $request->page_size ?? 10;
        $users = User::with('serviceProviderProfile')->paginate($pageSize);
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
