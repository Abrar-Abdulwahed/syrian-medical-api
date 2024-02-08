<?php

namespace App\Http\Controllers\User\Patient;

use App\Models\Product;
use App\Enums\OfferingType;
use Illuminate\Http\Request;
use App\Models\ProviderService;
use App\Services\Items\ReviewService;
use App\Http\Resources\ProductListResource;
use App\Http\Resources\ServiceListResource;
use App\Http\Controllers\User\BaseUserController;

class HomeController extends BaseUserController
{
    public function __construct(protected ReviewService $offerings)
    {
        parent::__construct();
        $this->middleware('bind.items.type')->only('show');
    }

    public function index(Request $request)
    {
        // show all items or filter by type
        $type = $request->query('type');
        if ($type === OfferingType::SERVICE->value) {
            $services = ProviderService::get();
            return $this->returnJSON(ServiceListResource::collection($services), __('message.data_retrieved', ['item' => __('message.services')]));
        } else if ($type === OfferingType::PRODUCT->value) {
            $products = Product::whereRelation('provider', 'activated', 1)->get();
            return $this->returnJSON(ProductListResource::collection($products), __('message.data_retrieved', ['item' => __('message.products')]));
        }
        return $type ? $this->offerings->getItemsByType($type) : $this->offerings->getAllItems();
    }

    public function show(Request $request)
    {
        return $this->offerings->getItemByType($request);
    }
}
