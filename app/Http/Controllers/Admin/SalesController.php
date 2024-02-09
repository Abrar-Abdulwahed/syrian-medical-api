<?php

namespace App\Http\Controllers\Admin;

use App\Actions\SearchAction;
use App\Models\Product;
use App\Enums\OrderStatus;
use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Http\Resources\SalesListResource;
use App\Http\Controllers\Admin\BaseAdminController;

class SalesController extends BaseAdminController
{
    public function __construct(protected SearchAction $searchAction)
    {
        parent::__construct();
    }

    public function __invoke(Request $request)
    {
        $query = Reservation::whereIn('status', [OrderStatus::PAID->value, OrderStatus::DELIVERED]);

        // Filter by month if the "month" parameter is provided
        if ($request->has('month')) {
            $month = $request->month;
            $query->whereMonth('updated_at', $month);
        }

        // Filter by year if the "year" parameter is provided
        if ($request->has('year')) {
            $year = $request->year;
            $query->whereYear('updated_at', $year);
        }

        // Filter by provider if the "provider_id" parameter is provided
        if ($request->has('provider_id')) {
            $query->where('provider_id', $request->provider_id);
        }

        // Filter by search if the "search" parameter is provided
        if ($request->has('search')) {
            $query = $this->searchAction->searchAction($query, $request->query('search'));
        }

        $sales = $query->get();
        $totalPrice = $query->sum('price');
        return $this->returnJSON([
            'sales' => SalesListResource::collection($sales),
            'subtotal_price' => $totalPrice,
        ], __('message.data_retrieved', ['item' => __('message.sales')]));
    }
}
