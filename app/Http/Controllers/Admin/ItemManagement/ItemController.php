<?php

namespace App\Http\Controllers\Admin\ItemManagement;

use App\Models\Product;
use App\Enums\OfferingType;
use Illuminate\Http\Request;
use App\Models\ProviderService;
use App\Actions\GetAllItemsAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ServiceListResource;

class ItemController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'activated', 'verified', 'is-admin']);
    }

    public function index(Request $request)
    {
        $type = $request->query('type');

        if ($type === OfferingType::SERVICE->value) {
            $services = ProviderService::get();
            return $this->returnJSON(ServiceListResource::collection($services), 'Data retrieved successfully');
        } else if ($type === OfferingType::PRODUCT->value) {
            $products = Product::with('provider')->get();
            return $this->returnJSON(ProductResource::collection($products), 'Data retrieved successfully');
        }

        // items(services and items) in general
        return (new GetAllItemsAction)->getData();
    }

    public function store(Request $request)
    {
        //
    }

    public function show(string $id)
    {
        //
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
