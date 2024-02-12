<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Filters\SalesFilter;
use App\Models\Reservation;
use App\Http\Resources\SalesListResource;
use App\Http\Controllers\Admin\BaseAdminController;

class SalesController extends BaseAdminController
{
    public function __invoke(SalesFilter $filters)
    {
        $query = Reservation::whereIn('status', [OrderStatus::PAID->value, OrderStatus::DELIVERED])->filter($filters);


        $sales = $query->get();
        $totalPrice = $query->sum('price');
        return $this->returnJSON([
            'sales' => SalesListResource::collection($sales),
            'subtotal_price' => $totalPrice,
        ], __('message.data_retrieved', ['item' => __('message.sales')]));
    }
}
