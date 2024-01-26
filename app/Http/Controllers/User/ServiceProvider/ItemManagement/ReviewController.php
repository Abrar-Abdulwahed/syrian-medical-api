<?php

namespace App\Http\Controllers\User\ServiceProvider\ItemManagement;


use App\Enums\OfferingType;
use Illuminate\Http\Request;
use App\Models\ProviderService;
use App\Http\Controllers\Controller;
use App\Services\Items\ReviewService;
use App\Http\Resources\ProductListResource;
use App\Http\Resources\ProviderServiceListResource;

class ReviewController extends Controller
{
    public function __construct(protected ReviewService $reviewService)
    {
        $this->middleware(['auth:sanctum', 'verified', 'activated']);
        $this->middleware('bind.items.type')->only('show');
    }

    public function index(Request $request)
    {
        // show all items or filter by type
        $type = $request->query('type');
        $user = $request->user();

        if ($type === OfferingType::SERVICE->value) {
            $services = $user->services()->get();
            return $this->returnJSON(ProviderServiceListResource::collection($services), 'Data retrieved successfully');
        } else if ($type === OfferingType::PRODUCT->value) {
            $products = $user->products->get();
            return $this->returnJSON(ProductListResource::collection($products), 'Data retrieved successfully');
        }

        $services = $user->services()->get();
        $products = $user->products()->get();
        $result =   ProductListResource::collection($products)->merge(ProviderServiceListResource::collection($services));
        return $this->returnJSON($result, 'Data retrieved successfully');
    }

    public function show(Request $request)
    {
        $this->authorize('view', $request->item);
        return $this->reviewService->getItemByType($request);
    }
}
