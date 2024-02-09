<?php

namespace App\Http\Controllers\Admin\ItemManagement;

use App\Models\Product;
use App\Enums\OfferingType;
use Illuminate\Http\Request;
use App\Actions\SearchAction;
use App\Models\ProviderService;
use App\Services\Items\ReviewService;
use App\Http\Resources\Item\ProductListResource;
use App\Http\Resources\Item\ServiceListResource;
use App\Http\Controllers\Admin\BaseAdminController;

class ReviewController extends BaseAdminController
{
    public function __construct(protected ReviewService $reviewService, protected SearchAction $searchAction)
    {
        parent::__construct();
        $this->middleware('bind.items.type')->only('show');
    }

    public function index(Request $request)
    {
        $type = $request->query('type');
        $services = [];
        $products = [];

        // filter by type
        if ($type === null || $type === OfferingType::SERVICE->value) {
            $query = ProviderService::query();
            $services = $this->reviewService->filterItems(ProviderService::class, $query, $request);
        }
        if ($type === null || $type === OfferingType::PRODUCT->value) {
            $query = Product::query();
            $products = $this->reviewService->filterItems(Product::class, $query, $request);
        }

        $result =   ProductListResource::collection($products)->merge(ServiceListResource::collection($services));
        return $this->returnJSON($result, __('message.data_retrieved', ['item' => __('message.items')]));
    }

    public function show(Request $request)
    {
        return $this->reviewService->getItemByType($request);
    }
}
