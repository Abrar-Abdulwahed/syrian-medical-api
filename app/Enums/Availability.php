<?php

namespace App\Enums;

enum Availability: string
{
    case AVAILABLE = "available";
    case BOOKED = "booked";

    public static function values(): array
    {
        return array_column(self::cases(), 'name', 'value');
    }
}
