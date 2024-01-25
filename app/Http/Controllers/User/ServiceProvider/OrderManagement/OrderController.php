<?php

namespace App\Http\Controllers\User\ServiceProvider\OrderManagement;

use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Models\ProviderService;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Http\Resources\ReservationResource;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'verified', 'activated']);
    }

    public function index(Request $request)
    {
        // show all items by status
        $type = $request->query('type');
        $user = $request->user();

        // Eager Loading
        $user->load([
            'products.reservations.morphReservation',
            'providerServices.reservations.morphReservation',
        ]);

        $reservations = $user->products->flatMap->reservations
            ->merge(
                $user->providerServices->flatMap->reservations
            )->sortByDesc('morphReservation.created_at');

        return $this->returnJSON(OrderResource::collection($reservations), 'Data retrieved successfully');
    }

    public function store(Request $request)
    {
        //
    }

    public function show(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
