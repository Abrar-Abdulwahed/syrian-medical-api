<?php

namespace App\Http\Controllers\Admin\ItemManagement;

use App\Models\Product;
use App\Enums\OfferingType;
use Illuminate\Http\Request;
use App\Filters\ItemFilter;
use App\Models\ProviderService;
use App\Services\Items\ReviewService;
use App\Http\Resources\Item\ProductListResource;
use App\Http\Resources\Item\ServiceListResource;
use App\Http\Controllers\Admin\BaseAdminController;

class ReviewController extends BaseAdminController
{
    public function __construct(protected ReviewService $reviewService)
    {
        parent::__construct();
        $this->middleware('bind.items.type')->only('show');
    }

    public function index(Request $request, ItemFilter $params)
    {
        $type = $request->query('type');
        $services = [];
        $products = [];

        // filter by type
        if ($type === null || $type === OfferingType::SERVICE->value) {
            $services = ProviderService::query()->filter($params)->get();
        }
        if ($type === null || $type === OfferingType::PRODUCT->value) {
            $products = Product::query()->filter($params)->get();
        }

        $result =   ProductListResource::collection($products)->merge(ServiceListResource::collection($services));
        return $this->returnJSON($result, __('message.data_retrieved', ['item' => __('message.items')]));
    }

    public function show(Request $request)
    {
        return $this->reviewService->getItemByType($request);
    }
}
