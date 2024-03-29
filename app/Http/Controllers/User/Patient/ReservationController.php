<?php

namespace App\Http\Controllers\User\Patient;

use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Models\ProviderService;
use App\Models\ProductReservation;
use App\Models\ServiceReservation;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\User\BaseUserController;
use App\Http\Requests\Patient\ReservationDestroyRequest;
use App\Http\Resources\ReservationResource;
use App\Notifications\ReservationNotification;
use App\Http\Requests\Patient\ReservationStoreRequest;

class ReservationController extends BaseUserController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('bind.items.type')->only('store');
        $this->authorizeResource(Reservation::class, 'reservation');
    }

    public function index(Request $request)
    {
        $user = $request->user()->load('reservations.reservationable');
        return $this->returnJSON(ReservationResource::collection($user->reservations), __('message.data_retrieved', ['item' => __('message.orders')]));
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
                return $this->returnWrong(__('message.time_not_available'));
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
            $price = $item->final_price ?? $item->price;
            $reservation = $startToReserve->morphReservation()->create($reservationData);
            $reservation->forceFill(['price' => $price, 'patient_id' => $request->user()->id, 'provider_id' => $item->provider->id])->save();
            $item->provider->notify(new ReservationNotification(true, $reservation));
            DB::commit();
            return $this->returnSuccess(__('message.completed', ['item' => __('message.order')]));
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->returnWrong($e->getMessage());
        }
    }

    // cancel reservation
    public function destroy(ReservationDestroyRequest $request, Reservation $reservation)
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
        return $this->returnSuccess(__('message.canceled', ['item' => __('message.reservation')]));
    }
}
