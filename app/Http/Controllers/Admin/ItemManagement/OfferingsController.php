<?php

namespace App\Http\Controllers\Admin\ItemManagement;

use Illuminate\Http\Request;
use App\Services\OfferingsService;
use App\Http\Controllers\Controller;

class OfferingsController extends Controller
{
    public function __construct(protected OfferingsService $offerings)
    {
        $this->middleware(['auth:sanctum', 'activated', 'verified', 'is-admin']);
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

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
