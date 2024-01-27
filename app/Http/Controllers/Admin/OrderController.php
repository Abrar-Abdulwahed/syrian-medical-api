<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Models\Reservation;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Resources\ReservationResource;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'activated', 'verified', 'is-admin']);
    }

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
