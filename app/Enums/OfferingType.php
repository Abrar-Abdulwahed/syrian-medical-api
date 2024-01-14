<?php

namespace App\Enums;

enum OfferingType: string{
    case PRODUCT = "product";
    case SERVICE = "service";

    public static function values(): array
    {
        return array_column(self::cases(), 'name', 'value');
    }
}