<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\ServiceReservation;
use Illuminate\Http\Resources\Json\JsonResource;

class AvailabilityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $isDateInPast = Carbon::parse($this->date)->isPast();
        $times = json_decode($this->times);
        if ($isDateInPast)
            return [
                'date'           => $this->date,
                'isDateAvailable' => false,
                'times'          => $times,
            ];
        else {
            $transformedTimes = collect($times)->map(function ($time) {
                return [
                    'time'       => $time,
                    'isAvailable' => ServiceReservation::isAvailable($this->date, $time),
                ];
            });
            $isDateAvailable = $transformedTimes->contains('isAvailable', true);
            return [
                'date'  => $this->date,
                'isDateAvailable' => $isDateAvailable,
                'times' => $transformedTimes->toArray(),
            ];
        }
    }
}
