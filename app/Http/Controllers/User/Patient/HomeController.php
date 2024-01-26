<?php

namespace App\Http\Controllers\User\Patient;

use Illuminate\Http\Request;
use App\Services\Items\ReviewService;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function __construct(protected ReviewService $offerings)
    {
        $this->middleware(['auth:sanctum', 'verified', 'activated']);
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
