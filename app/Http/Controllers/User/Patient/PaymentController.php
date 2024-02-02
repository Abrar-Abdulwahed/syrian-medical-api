<?php

namespace App\Http\Controllers\User\Patient;

use App\Models\Admin;
use App\Enums\OrderStatus;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\PatientPayNotification;
use Stripe\{Stripe, Charge, StripeClient, Token};
use App\Notifications\PaymentNotForPendingOrdersNotification;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'verified', 'activated']);
    }

    public function __invoke(Request $request)
    {
        $user = $request->user();

        $query = $user->reservations()->whereIn('status', [OrderStatus::PENDING->value, OrderStatus::ACCEPTED])->get();
        $acceptedOrders = $query->where('status', OrderStatus::ACCEPTED->value);
        $pendingOrders = $query->where('status', OrderStatus::PENDING->value);
        if ($pendingOrders->count() > 0) {
            $user->notify(new PaymentNotForPendingOrdersNotification);
        }
        if ($acceptedOrders->count() > 0) {
            foreach ($acceptedOrders as $order) {
                // Decode JSON string
                $data = json_decode($order->payment_method, true);
                $cardType = key($data);
                $cardInfo = $data[$cardType];
                $cardNumber = $cardInfo['card_number'];
                $expMonth = $cardInfo['expiration_month'];
                $expYear = $cardInfo['expiration_year'];
                $cvv = $cardInfo['cvv'];

                Stripe::setApiKey(env('STRIPE_SECRET'));
                $stripe = new StripeClient(env('STRIPE_SECRET'));
                try {
                    // $token = Token::create([
                    //     'card' => [
                    //         'number'    => $cardNumber,
                    //         'exp_month' => $expMonth,
                    //         'exp_year'  => $expYear,
                    //         'cvc'       => $cvv,
                    //     ],
                    // ]);
                    //! 'source' => 'tok_visa' : is for test token recommended by new stripe version
                    $response = $stripe->charges->create([
                        'amount' => round($order->price),
                        'currency' => 'usd',
                        'source' => 'tok_visa', // $token->id
                        'description' => 'Test Payment',
                    ]);

                    $order->forceFill(['status' => OrderStatus::PAID->value])->save();
                    // Notify provider
                    $order->provider->notify(new PatientPayNotification($order));

                    // Notify admin
                    Admin::find(1)->notify(new PatientPayNotification($order));
                    return $this->returnSuccess($response->status);
                } catch (\Exception $e) {
                    return $this->returnWrong($e->getMessage());
                }
            }
        }
    }
}
