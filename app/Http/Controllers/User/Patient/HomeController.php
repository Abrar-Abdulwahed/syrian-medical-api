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
    }

    public function index(Request $request)
    {
        // show all items or filter by type
        $type = $request->query('type');
        return $type ? $this->offerings->getItemsByType($type) : $this->offerings->getAllItems();
    }

    public function show(string $type, string $id)
    {
        return $this->offerings->getItemByType($id, $type);
    }
}
