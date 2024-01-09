<?php

namespace App\Enums;

enum ServiceProviderCategory: string{
    case DOCTOR = "doctor";
    case PHARMACY = "pharmacy";
    case LABORATORY = "laboratory";
    case CLINIC = "clinic";

    public static function values(): array
    {
        return array_column(self::cases(), 'name', 'value');
    }
}