<?php

namespace App\Http\Controllers\User\ServiceProvider\ItemManagement;

use Illuminate\Http\Request;
use App\Services\Items\ReviewService;
use App\Http\Resources\Item\ProductListResource;
use App\Http\Controllers\User\BaseUserController;
use App\Http\Resources\Item\ServiceListResource;

class ReviewController extends BaseUserController
{
    public function __construct(protected ReviewService $reviewService)
    {
        parent::__construct();
        $this->middleware('bind.items.type')->only('show');
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $services = $user->providerServices->load('service');
        $products = $user->products;
        $result =   ProductListResource::collection($products)->merge(ServiceListResource::collection($services));
        return $this->returnJSON($result, __('message.data_retrieved', ['item' => __('message.products_services')]));
    }

    public function show(Request $request)
    {
        $this->authorize('view', $request->item);
        return $this->reviewService->getItemByType($request);
    }
}
