<?php

namespace App\Http\Controllers\User\ServiceProvider\ItemManagement;


use App\Enums\OfferingType;
use Illuminate\Http\Request;
use App\Http\Controllers\User\BaseUserController;
use App\Services\Items\ReviewService;
use App\Http\Resources\ProductListResource;
use App\Http\Resources\ProviderServiceListResource;

class ReviewController extends BaseUserController
{
    public function __construct(protected ReviewService $reviewService)
    {
        parent::__construct();
        $this->middleware('bind.items.type')->only('show');
    }

    public function index(Request $request)
    {
        // show all items or filter by type
        $type = $request->query('type');
        $user = $request->user();

        if ($type === OfferingType::SERVICE->value) {
            $services = $user->services()->get();
            return $this->returnJSON(ProviderServiceListResource::collection($services), __('message.data_retrieved', ['item' => __('message.services')]));
        } else if ($type === OfferingType::PRODUCT->value) {
            $products = $user->products;
            return $this->returnJSON(ProductListResource::collection($products), __('message.data_retrieved', ['item' => __('message.products')]));
        }

        $services = $user->services()->get();
        $products = $user->products()->get();
        $result =   ProductListResource::collection($products)->merge(ProviderServiceListResource::collection($services));
        return $this->returnJSON($result, __('message.data_retrieved', ['item' => __('message.products_services')]));
    }

    public function show(Request $request)
    {
        $this->authorize('view', $request->item);
        return $this->reviewService->getItemByType($request);
    }
}
