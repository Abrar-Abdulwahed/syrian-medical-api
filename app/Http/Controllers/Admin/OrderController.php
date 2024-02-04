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
        return $this->returnJSON(ReservationResource::collection($orders), __('message.data_retrieved'));
    }

    public function show(Reservation $reservation)
    {
        $reservation->load('rejectionReason');
        return $this->returnJSON(new ReservationResource($reservation), __('message.data_retrieved'));
    }
}
