<?php

namespace App\Http\Resources\Item;

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

        if ($isDateInPast) {
            return [
                'date' => $this->date,
                'isDateAvailable' => false,
                'times' => $times,
            ];
        } else {
            sort($times);

            $transformedTimes = collect($times)->map(function ($time) {
                return [
                    'time' => $time,
                    'isAvailable' => ServiceReservation::isAvailable($this->date, $time),
                ];
            })->toArray();

            $isDateAvailable = in_array(true, array_column($transformedTimes, 'isAvailable'));

            return [
                'date' => $this->date,
                'isDateAvailable' => $isDateAvailable,
                'times' => $transformedTimes,
            ];
        }
    }
}
