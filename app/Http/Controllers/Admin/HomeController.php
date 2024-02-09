<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OfferingType;
use Carbon\Carbon;
use App\Models\User;
use App\Enums\UserType;
use App\Enums\OrderStatus;
use App\Models\Reservation;
use App\Http\Controllers\Admin\BaseAdminController;

class HomeController extends BaseAdminController
{
    public function __invoke()
    {
        $savingsData = $this->populateSavingsDataByDayAndType();
        $statistics = $this->statistics();
        $data = ['totalSavings' => $savingsData['totalPrice'], 'savingsData' => $savingsData['details'], 'statistics' => $statistics];
        return $this->returnJSON($data);
    }

    private function populateSavingsDataByDayAndType()
    {
        $startDate = now()->startOfMonth();
        $endDate = now()->endOfMonth();

        // Query to get the savings for each day among the month
        $savingsData = Reservation::whereIn('status', [OrderStatus::PAID, OrderStatus::DELIVERED])
            ->whereBetween('updated_at', [$startDate, $endDate])
            ->get(['updated_at', 'price', 'reservationable_type']);

        $groupedData = [];

        $daysOfWeek = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        $types = [OfferingType::SERVICE->value, OfferingType::PRODUCT->value];
        $totalSavings = 0;

        // Set initial values to 0 for each day and type
        $groupedData = array_fill_keys($daysOfWeek, array_fill_keys($types, 0));

        foreach ($savingsData as $item) {
            $day = $item->updated_at->format('D');
            $type = $item->reservationable_type === 'ServiceReservation' ? OfferingType::SERVICE->value : OfferingType::PRODUCT->value;
            $totalSavings += $item->price;
            $groupedData[$day][$type] += $item->price;
        }

        return [
            'totalPrice' => $totalSavings,
            'details'   => $groupedData,
        ];
    }

    private function statistics()
    {
        $application_visitors = User::count();
        $new_customers = User::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $total_orders = Reservation::count();

        $statistics = [
            ['label' => __('others.new_customers'), 'value' => $new_customers],
            ['label' => __('others.application_visitors'), 'value' => $application_visitors],
            ['label' => __('others.total_orders'), 'value' => $total_orders],
        ];
        return $statistics;
    }
}
