<?php

namespace App\Http\Controllers\User\ServiceProvider;

use App\Enums\OrderStatus;
use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ReservationResource;
use App\Notifications\ProviderReviewOrderNotification;
use App\Http\Requests\ServiceProvider\OrderAcceptRequest;
use App\Http\Requests\ServiceProvider\OrderRejectRequest;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'verified', 'activated']);
        $this->middleware('bind.reservation.type')->only('accept');
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
            $orders = $user->orders;

            // Filter by status if the "status" parameter is provided
            if ($status !== null) {
                $orders = $orders->where('morphReservation.status', $status);
            }

            $orders = $orders->sortByDesc('morphReservation.created_at');

            return $this->returnJSON(ReservationResource::collection($orders), __('message.data_retrieved', ['item' => __('message.orders')]));
        } catch (\Exception $e) {
            return $this->returnWrong($e->getMessage());
        }
    }

    public function show(Reservation $reservation)
    {
        try {
            $this->authorize('manage-reservations', $reservation);
            $reservation->load('rejectionReason');
            // if ($orders instanceof ServiceReservation) {
            //     $orders->load('service.availabilities');
            // }
            return $this->returnJSON(new ReservationResource($reservation), __('message.data_retrieved', ['item' => __('message.order')]));
        } catch (\Exception $e) {
            return $this->returnWrong($e->getMessage());
        }
    }

    public function accept(OrderAcceptRequest $request)
    {
        $reservation = $request->reservation;
        try {
            $this->authorize('manage-reservations', $reservation);
            $reservation->forceFill(['status' => OrderStatus::ACCEPTED->value])->save();

            $reservation->patient->notify(new ProviderReviewOrderNotification(true, $reservation));
            return $this->returnSuccess(__('message.accepted', ['item' => __('message.order')]));
        } catch (\Exception $e) {
            return $this->returnWrong($e->getMessage());
        }
    }

    public function refuse(OrderRejectRequest $request, string $id)
    {
        $reservation = Reservation::findOrFail($id);
        try {
            $this->authorize('manage-reservations', $reservation);
            $reservation->forceFill(['status' => OrderStatus::CANCELED->value])->save();
            $reservation->rejectionReason()->updateOrCreate(['rejection_reason' => $request->rejection_reason]);
            $reservation->patient->notify(new ProviderReviewOrderNotification(false, $reservation));
            return $this->returnSuccess(__('message.canceled', ['item' => __('message.order')]));
        } catch (\Exception $e) {
            return $this->returnWrong($e->getMessage());
        }
    }
}
