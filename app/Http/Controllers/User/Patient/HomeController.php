<?php

namespace App\Http\Controllers\User\Patient;

use Illuminate\Http\Request;
use App\Services\Items\ReviewService;
use App\Http\Controllers\User\BaseUserController;

class HomeController extends BaseUserController
{
    public function __construct(protected ReviewService $offerings)
    {
        parent::__construct();
        $this->middleware('bind.items.type')->only('show');
    }

    public function index(Request $request)
    {
        // show all items or filter by type
        $type = $request->query('type');
        return $type ? $this->offerings->getItemsByType($type) : $this->offerings->getAllItems();
    }

    public function show(Request $request)
    {
        return $this->offerings->getItemByType($request);
    }
}
