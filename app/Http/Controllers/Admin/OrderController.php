<?php

namespace App\Http\Controllers\Admin;

use App\Models\Reservation;
use App\Http\Controllers\Admin\BaseAdminController;
use App\Http\Resources\ReservationResource;

class OrderController extends BaseAdminController
{
    public function index()
    {
        $orders = Reservation::get();
        return $this->returnJSON(ReservationResource::collection($orders), 'Data retrieved successfully');
    }

    public function show(Reservation $reservation)
    {
        $reservation->load('rejectionReason');
        return $this->returnJSON(new ReservationResource($reservation), 'Data retrieved successfully');
    }
}
