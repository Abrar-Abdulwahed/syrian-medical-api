<?php

namespace App\Enums;

enum OfferingType: string
{
    case PRODUCT = "product";
    case SERVICE = "service";

    public static function values(): array
    {
        return array_column(self::cases(), 'name', 'value');
    }

    public static function localizedValues(): array
    {
        $localizedValues = [];

        foreach (self::cases() as $case) {
            $localizedValues[$case->value] = [
                'en' => __("enums.offering_type.{$case->value}"),
                'ar' => __("enums.offering_type_ar.{$case->value}"),
            ];
        }

        return $localizedValues;
    }
}
