<?php

namespace App\Enums;

enum UserType: string
{
    case PATIENT = "patient";
    case SERVICE_PROVIDER = "service-provider";

    public static function values(): array
    {
        return array_column(self::cases(), 'name', 'value');
    }

    public static function localizedValues(): array
    {
        $localizedValues = [];

        foreach (self::cases() as $case) {
            $localizedValues[$case->value] = [
                'en' => __("others.user_type.{$case->value}"),
                'ar' => __("others.user_type.{$case->value}"),
            ];
        }

        return $localizedValues;
    }
}
