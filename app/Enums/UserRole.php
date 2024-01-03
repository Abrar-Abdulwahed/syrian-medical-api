<?php

namespace App\Enums;

enum UserRole: string{
    case SUPER_ADMIN = "super-admin";
    case ADMIN = "admin";
    case PATIENT = "patient";
    case SERVICE_PROVIDER = "service-provider";

    public static function values(): array
    {
        return array_column(self::cases(), 'name', 'value');
    }
}