<?php

namespace App\Enums;

enum OrderStatus: string
{
    case PENDING   = "pending";
    case ACCEPTED  = "accepted";
    case PAID      = "paid";
    case DELIVERED = "delivered";
    case COMPLETED = "completed";
    case CANCELED  = "canceled";

    // pending => في انتظار القبول أو الرفض
    // accepted => تم القبول ولم يتم الدفع
    // paid => تم الدفع ولم يتم التسليم
    // delivered => تم التسليم
    //(الوصول لحالة التسليم معناه (تم القبول، تم الدفع، تم التسليم))
    // canceled => تم الرفض

    public static function values(): array
    {
        return array_column(self::cases(), 'name', 'value');
    }

    public static function localizedValues(): array
    {
        $localizedValues = [];

        foreach (self::cases() as $case) {
            $localizedValues[$case->value] = [
                'en' => __("others.order_status.{$case->value}"),
                'ar' => __("others.order_status.{$case->value}"),
            ];
        }

        return $localizedValues;
    }
}
