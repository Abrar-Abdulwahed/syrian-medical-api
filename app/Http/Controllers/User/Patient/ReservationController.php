<?php

namespace App\Http\Controllers\User\Patient;

use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Models\ProviderService;
use App\Models\ServiceReservation;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\ReservationResource;
use App\Notifications\ReservationNotification;
use App\Http\Requests\Patient\ReservationStoreRequest;
use App\Models\ProductReservation;

class ReservationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'verified', 'activated']);
        $this->middleware('bind.items.type')->only('store');
        $this->authorizeResource(Reservation::class, 'reservation');
    }

    public function index(Request $request)
    {
        $user = $request->user()->load('reservations.reservationable');
        return $this->returnJSON(ReservationResource::collection($user->reservations));
    }

    public function show(Reservation $reservation)
    {
        return $this->returnJSON(new ReservationResource($reservation));
    }

    // make reservation
    public function store(ReservationStoreRequest $request)
    {
        DB::beginTransaction();
        try {
            $validatedData = $request->safe()->except(['latitude', 'longitude', 'appointment_date', 'appointment_time']);
            $locationData = $request->safe()->only(['latitude', 'longitude']);

            $item = $request->item;

            // Ensure if date, time are available
            if ($item instanceof ProviderService && !ServiceReservation::isAvailable($request->appointment_date, $request->appointment_time)) {
                DB::rollBack();
                return $this->returnWrong('This time is not available right now!');
            }

            // otherwise
            $reservationData = array_merge($validatedData, ['location' => $locationData]);
            if ($item instanceof ProviderService) {
                $startToReserve = $item->reservations()->create(
                    [
                        'appointment_date' => $request->safe()->appointment_date,
                        'appointment_time' => $request->safe()->appointment_time,
                    ]
                );
            } else {
                $startToReserve = $item->reservations()->updateOrCreate(
                    ['product_id' => $item->id],
                    [
                        'quantity' => DB::raw('quantity + 1'),
                    ]
                );
            }
            $reservation = $startToReserve->morphReservation()->create($reservationData);
            $reservation->forceFill(['patient_id' => $request->user()->id])->save();
            $item->provider->notify(new ReservationNotification(true, $reservation));
            DB::commit();
            return $this->returnSuccess('You\'ve completed your Order');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->returnWrong($e->getMessage());
        }
    }

    // cancel reservation
    public function destroy(Reservation $reservation)
    {
        $typeReservation = $reservation->reservationable; // ProductReservation or ServiceReservation
        $provider = $typeReservation->provider;
        $provider->notify(new ReservationNotification(false, $reservation));

        if ($typeReservation instanceof ProductReservation) {
            $typeReservation->quantity--;

            if ($typeReservation->quantity <= 0) {
                $typeReservation->delete();
            } else {
                $typeReservation->save();
            }
        } else {
            $typeReservation->delete();
        }
        $reservation->delete(); // morph reservation
        return $this->returnSuccess('You\'ve canceled your reservation');
    }
}
