<?php

namespace App\Enums;

enum UserType: string{
    case PATIENT = "patient";
    case SERVICE_PROVIDER = "service-provider";

    public static function values(): array
    {
        return array_column(self::cases(), 'name', 'value');
    }
}