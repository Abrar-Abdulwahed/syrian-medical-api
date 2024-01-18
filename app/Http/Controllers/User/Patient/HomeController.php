<?php

namespace App\Http\Controllers\User\Patient;

use App\Models\User;
use App\Models\Product;
use App\Models\Service;
use Illuminate\Http\Request;
use App\Models\ProviderService;
use App\Actions\GetAllItemsAction;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ServiceListResource;
use App\Http\Resources\ServiceReviewResource;
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
        return (new GetAllItemsAction)->getData();
    }

    public function showProduct(Product $product)
    {
        return $this->returnJSON(new ProductResource($product), 'Data retrieved successfully');
    }

    public function showService(ProviderService $providerService)
    {
        return $this->returnJSON(new ServiceReviewResource($providerService), 'Data retrieved successfully');
    }
}
