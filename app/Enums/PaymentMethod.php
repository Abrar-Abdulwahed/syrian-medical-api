<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case VISA = "visa";
    case MASTER_CARD = "mastercard";

    public static function values(): array
    {
        return array_column(self::cases(), 'name', 'value');
    }
}
