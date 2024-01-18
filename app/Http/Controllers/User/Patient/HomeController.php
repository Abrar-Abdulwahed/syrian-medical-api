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
use App\Http\Resources\ServiceReviewResource;
use App\Http\Resources\ServiceListResource;
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
        $result =   ProductResource::collection($products)->merge(ServiceListResource::collection($services));
        return $this->returnJSON($result, 'Data retrieved successfully');
    }

    public function store(Request $request)
    {
        //
    }

    public function showProduct(Product $product)
    {
        // Load additional data if needed
        $product->load('provider');
        return $this->returnJSON(new ProductResource($product), 'Data retrieved successfully');
    }

    public function showService(ProviderService $providerService)
    {
        return $this->returnJSON(new ServiceReviewResource($providerService), 'Data retrieved successfully');
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
