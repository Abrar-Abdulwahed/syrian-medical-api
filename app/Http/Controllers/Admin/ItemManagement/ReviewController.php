<?php

namespace App\Http\Controllers\Admin\ItemManagement;


use Illuminate\Http\Request;
use App\Services\Items\ReviewService;
use App\Http\Controllers\Controller;

class ReviewController extends Controller
{
    public function __construct(protected ReviewService $reviewService)
    {
        $this->middleware(['auth:sanctum', 'activated', 'verified', 'is-admin']);
        $this->middleware('bind.items.type')->only('show');
    }

    public function index(Request $request)
    {
        // show all items or filter by type
        $type = $request->query('type');
        return $type ? $this->reviewService->getItemsByType($type) : $this->reviewService->getAllItems();
    }

    public function show(Request $request)
    {
        return $this->reviewService->getItemByType($request);
    }
}
