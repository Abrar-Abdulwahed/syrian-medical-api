<?php

namespace App\Http\Controllers\User\ServiceProvider\OrderManagement;

use App\Enums\OrderStatus;
use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Models\ServiceReservation;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Http\Requests\ServiceProvider\RejectionReasonRequest;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'verified', 'activated']);
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $orders = [];
        $status = $request->query('status');
        try {
            $user->load([
                'products.reservations.morphReservation',
                'providerServices.reservations.morphReservation',
            ]);
            $orders = $user->products->flatMap->reservations
                ->merge($user->providerServices->flatMap->reservations)
                ->where('morphReservation.status', $status)
                ->sortByDesc('morphReservation.created_at');
            return $this->returnJSON(OrderResource::collection($orders), 'Data retrieved successfully');
        } catch (\Exception $e) {
            return $this->returnWrong($e->getMessage());
        }
    }

    public function show(Reservation $reservation)
    {
        try {
            $this->authorize('manage-reservations', $reservation);
            $orders = $reservation->reservationable;
            if ($orders instanceof ServiceReservation) {
                $orders->load('service.availabilities');
            }
            return $this->returnJSON(new OrderResource($orders), 'Data retrieved successfully');
        } catch (\Exception $e) {
            return $this->returnWrong($e->getMessage());
        }
    }

    public function accept(string $id)
    {
        $reservation = Reservation::findOrFail($id);
        try {
            $this->authorize('manage-reservations', $reservation);
            $reservation->forceFill(['status' => OrderStatus::COMPLETED->value])->save();

            //TODO: Notify patient
            return $this->returnSuccess('You Mark this order as completed');
        } catch (\Exception $e) {
            return $this->returnWrong($e->getMessage());
        }
    }

    public function refuse(RejectionReasonRequest $request, string $id)
    {
        $reservation = Reservation::findOrFail($id);
        try {
            $this->authorize('manage-reservations', $reservation);
            $reservation->forceFill(['status' => OrderStatus::CANCELED->value])->save();
            $reservation->rejectionReason()->updateOrCreate(['rejection_reason' => $request->rejection_reason]);
            //TODO: Notify patient
            return $this->returnSuccess('You Mark this order as canceled');
        } catch (\Exception $e) {
            return $this->returnWrong($e->getMessage());
        }
    }
}
