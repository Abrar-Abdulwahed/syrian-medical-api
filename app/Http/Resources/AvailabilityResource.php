<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use App\Models\Reservation;
use Illuminate\Http\Request;
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

        // If the date is in the past, consider it as unavailable
        if ($isDateInPast) {
            return [
                'date'           => $this->date,
                'isDateAvailable' => false,
                'times'          => json_decode($this->times),
            ];
        }
        //! Example of returned data
        // {
        //     "date": "2024-01-14",
        //     "isDateAvailable": false,
        //     "times": [
        //         "18:09:00",
        //         "19:00:00"
        //     ]
        // },
        // {
        //     "date": "2024-01-21",
        //     "isDateAvailable": false,
        //     "times": [
        //         "20:30:40"
        //     ]
        // }


        /*
        ? OTHERWISE
        */

        // Decode the times
        $times = json_decode($this->times);

        // Transform each time, indicating availability
        $transformedTimes = collect($times)->map(function ($time) {
            return [
                'time'       => $time,
                'isAvailable' => Reservation::isAvailable($this->date, $time),
            ];
        });

        $isDateAvailable = $transformedTimes->contains('isAvailable', true);

        return [
            'date'  => $this->date,
            'isDateAvailable' => $isDateAvailable,
            'times' => $transformedTimes->toArray(),
        ];

        //! Example of returned data
        // {
        //     "date": "2024-02-14",
        //     "isDateAvailable": true,
        //     "times": [
        //         {
        //             "time": "18:09:00",
        //             "isAvailable": false
        //         },
        //         {
        //             "time": "19:00:00",
        //             "isAvailable": true
        //         }
        //     ]
        // },
        // {
        //     "date": "2024-02-21",
        //     "isDateAvailable": false,
        //     "times": [
        //         {
        //             "time": "20:30:40",
        //             "isAvailable": false
        //         }
        //     ]
        // }
    }
}
