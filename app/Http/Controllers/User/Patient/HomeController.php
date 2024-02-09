<?php

namespace App\Http\Controllers\User\Patient;

use App\Actions\SearchAction;
use App\Models\Product;
use App\Models\Category;
use App\Enums\OfferingType;
use Illuminate\Http\Request;
use App\Models\ProviderService;
use Illuminate\Support\Facades\DB;
use App\Services\Items\ReviewService;
use App\Http\Resources\Item\ProductListResource;
use App\Http\Resources\Item\ServiceListResource;
use App\Http\Resources\CategoryListResource;
use App\Http\Controllers\User\BaseUserController;
use App\Http\Resources\DoctorSpecializationListResource;

class HomeController extends BaseUserController
{
    public function __construct(protected ReviewService $reviewService, protected SearchAction $searchAction)
    {
        parent::__construct();
        $this->middleware('bind.items.type')->only('show');
    }

    public function index(Request $request)
    {
        $type = $request->query('type'); // service or product
        $services = [];
        $products = [];

        // filter by type, show items whose owner are activated only
        if ($type === null || $type === OfferingType::SERVICE->value) {
            $query = ProviderService::query()->whereRelation('provider', 'activated', 1);
            $services = $this->reviewService->filterItems(ProviderService::class, $query, $request);
        }
        if ($type === null || $type === OfferingType::PRODUCT->value) {
            $query = Product::query()->whereRelation('provider', 'activated', 1);
            $products = $this->reviewService->filterItems(Product::class, $query, $request);
        }
        $items =  ProductListResource::collection($products)->merge(ServiceListResource::collection($services));

        // Fetch categories and doctors
        $categories = Category::get();
        $doctors = DB::table('doctor_specializations')->get();

        $result = [
            'products_services' => $items,
            'categories' => CategoryListResource::collection($categories),
            'doctors_specialist' => DoctorSpecializationListResource::collection($doctors),
        ];

        return $this->returnJSON($result, __('message.data_retrieved', ['item' => __('message.items')]));
    }

    public function show(Request $request)
    {
        return $this->reviewService->getItemByType($request);
    }
}
