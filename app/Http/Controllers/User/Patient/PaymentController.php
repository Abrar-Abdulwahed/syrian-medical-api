<?php

namespace App\Http\Controllers\User\Patient;

use App\Enums\OrderStatus;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'verified', 'activated']);
    }

    public function __invoke(Request $request)
    {
        $user = $request->user();
        $query = $user->reservations()->whereIn('status', [OrderStatus::PAID->value, OrderStatus::ACCEPTED, OrderStatus::CANCELED])->get();
        $acceptedOrders = $query->where('status', OrderStatus::ACCEPTED->value);
        $pendingOrders = $query->where('status', OrderStatus::PENDING->value);
        if ($pendingOrders->count() > 0) {
            //TODO: notify patient that payment will be made only on accepted orders
        }
        if ($acceptedOrders->count() > 0) {
            //TODO: implement payment
            //TODO: Send a notice to the Admin to all service providers & successfully paid for these orders (edit in DB)
        }
        // $rejectedOrders = $query->where('status', OrderStatus::CANCELED->value);
    }
}
