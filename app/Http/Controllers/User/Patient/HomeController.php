<?php

namespace App\Http\Controllers\User\Patient;

use App\Models\User;
use App\Models\Product;
use App\Models\Service;
use Illuminate\Http\Request;
use App\Models\ProviderService;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ServiceResource;
use App\Http\Resources\ProviderServiceResource;
use Illuminate\Pagination\LengthAwarePaginator;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'verified', 'activated']);
    }

    public function index()
    {
        $products = Product::with('provider')->get();
        $services = ProviderService::get();
        $result =   ProviderServiceResource::collection($services)->merge(ProductResource::collection($products));
        return $this->returnJSON($result, 'Data retrieved successfully');
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
