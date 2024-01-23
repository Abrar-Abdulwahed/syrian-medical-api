<?php

namespace App\Http\Controllers\User\Patient;

use Carbon\Carbon;
use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Models\ProviderService;
use Illuminate\Support\Facades\DB;
use App\Models\ServiceAvailability;
use App\Http\Controllers\Controller;
use App\Http\Resources\ReservationResource;
use App\Http\Requests\Patient\ReservationStoreRequest;

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
        return $this->returnJSON(ReservationResource::collection($request->user()->reservations()->get()));
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
            if ($item instanceof ProviderService && !Reservation::isAvailable($request->appointment_date, $request->appointment_time)) {
                DB::rollBack();
                return $this->returnWrong('This time is not available right now!');
            }

            $reservationData = array_merge($validatedData, ['location' => $locationData]);
            $reservation = $item->reservations()
                ->create($reservationData);
            $reservation->forceFill(['patient_id' => $request->user()->id])
                ->save();
            if ($item instanceof ProviderService) {
                $reservation->update(
                    [
                        'appointment_date' => $request->safe()->appointment_date,
                        'appointment_time' => $request->safe()->appointment_time,
                    ]
                );
            }
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
        dd($reservation);
        $reservation->delete();
        return $this->returnSuccess('You\'ve canceled your reservation');
    }
}
