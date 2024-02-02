<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\BaseAdminController;
use App\Http\Resources\ReservationResource;

class SalesController extends BaseAdminController
{
    public function index()
    {
        $query = Reservation::whereIn('status', [OrderStatus::PAID->value, OrderStatus::DELIVERED])->get();
        return $this->returnJSON(ReservationResource::collection($query), 'Data retrieved successfully');
    }

    public function show(string $id)
    {
        //
    }
}
