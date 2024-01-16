<?php

namespace App\Http\Controllers\User\Patient;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\ProviderService;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ServiceResource;
use Illuminate\Pagination\LengthAwarePaginator;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'verified', 'activated']);
    }

    public function index(Request $request)
    {
        $pageSize = $request->per_page ?? 10;

        $products = Product::with('provider')->get();
        $services = ProviderService::get();
        $mergedResult = $services->merge($products);

        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $slicedItems = $mergedResult->slice(($currentPage - 1) * $pageSize, $pageSize);
        $paginatedItems = new LengthAwarePaginator($slicedItems, $mergedResult->count(), $pageSize, $currentPage);

        $paginatedItems->setPath($request->url());

        [$meta, $links] = $this->paginateResponse($paginatedItems);

        $result = $slicedItems->map(function ($item) {
            if ($item instanceof Product) {
                return new ProductResource($item);
            } elseif ($item instanceof ProviderService) {
                return new ServiceResource($item);
            }
        });
        return $this->returnAllDataJSON($result, $meta, $links, 'Data retrieved successfully');
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
