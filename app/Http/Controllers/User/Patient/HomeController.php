<?php

namespace App\Http\Controllers\User\Patient;

use App\Models\Product;
use App\Models\Category;
use App\Enums\OfferingType;
use Illuminate\Http\Request;
use App\Models\ProviderService;
use Illuminate\Support\Facades\DB;
use App\Services\Items\ReviewService;
use App\Http\Resources\ProductListResource;
use App\Http\Resources\ServiceListResource;
use App\Http\Resources\CategoryListResource;
use Database\Seeders\DoctorSpecializationSeeder;
use App\Http\Controllers\User\BaseUserController;
use App\Http\Resources\DoctorSpecializationListResource;

class HomeController extends BaseUserController
{
    public function __construct(protected ReviewService $offerings)
    {
        parent::__construct();
        $this->middleware('bind.items.type')->only('show');
    }

    public function index(Request $request)
    {
        $type = $request->query('type'); // service or product
        $category = $request->query('category'); //filter by category
        $services = [];
        $products = [];

        // filter by type (if filter by category it means show services list)
        if ($category || $type === OfferingType::SERVICE->value) {
            $services = $this->filter(ProviderService::class, $request);
        } else if ($type === OfferingType::PRODUCT->value) {
            $products = $this->filter(Product::class, $request);
        } else {
            $services = $this->filter(ProviderService::class, $request);
            $products = $this->filter(Product::class, $request);
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

    public function filter($model, $request)
    {
        // show items whose provider are activated
        $query = $model::query();
        $query->whereRelation('provider', 'activated', 1);

        // filter by search, category
        $searchTerm = $request->query('search');
        $category = $request->query('category');
        $query->when($searchTerm, function ($query) use ($searchTerm) {
            $query->where(function ($query) use ($searchTerm) {
                $query->search($searchTerm);
            });
        });

        $query->when($category, function ($query) use ($category) {
            $query->where(function ($query) use ($category) {
                $query->category($category);
            });
        });
        return $query->get();
    }

    public function show(Request $request)
    {
        return $this->offerings->getItemByType($request);
    }
}
