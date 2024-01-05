<?php

namespace App\Enums;

enum AdminRole: string{
    case SUPER_ADMIN = "super-admin";
    case SUPERVISOR = "supervisor";

    public static function values(): array
    {
        return array_column(self::cases(), 'name', 'value');
    }
}