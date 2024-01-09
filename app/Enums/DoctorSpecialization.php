<?php

namespace App\Enums;

enum DoctorSpecialization: string{
    case DENTAL = "dental";
    case OPTICS = "optics";
    case NUTRITIONIST = "nutritionist";
    case HOME_NURSE = "home-nurse";
    case PLASTIC_SURGERY = "plastic-surgery";
    case X_RAYS = "x-rays";
    case COSMETOLOGY = "cosmetology";

    public static function values(): array
    {
        return array_column(self::cases(), 'name', 'value');
    }
}