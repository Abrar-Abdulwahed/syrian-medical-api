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
        $type = $request->query('type');
        $services = [];
        $products = [];

        if ($type === OfferingType::SERVICE->value) {
            $services = $this->getItemsByType(ProviderService::class, $request);
        } else if ($type === OfferingType::PRODUCT->value) {
            $products = $this->getItemsByType(Product::class, $request);
        } else {
            $services = $this->getItemsByType(ProviderService::class, $request);
            $products = $this->getItemsByType(Product::class, $request);
        }
        $result =  ProductListResource::collection($products)->merge(ServiceListResource::collection($services));
        return $this->returnJSON($result, __('message.data_retrieved', ['item' => __('message.products_services')]));
    }

    public function getItemsByType($model, $request)
    {
        // show items whose provider are activated
        $query = $model::query();
        $searchTerm = $request->query('search');
        $query->whereRelation('provider', 'activated', 1);
        $query->when($searchTerm, function ($query) use ($searchTerm) {
            $query->where(function ($query) use ($searchTerm) {
                $query->search($searchTerm);
            });
        });
        return $query->get();
    }

    public function show(Request $request)
    {
        return $this->offerings->getItemByType($request);
    }
}
