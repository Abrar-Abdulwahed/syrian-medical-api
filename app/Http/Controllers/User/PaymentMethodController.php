<?php

namespace App\Http\Controllers\User;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PaymentMethodResource;
use App\Http\Requests\Profile\PaymentMethodStoreRequest;

class PaymentMethodController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'verified', 'activated']);
    }

    public function index(Request $request)
    {
        $paymentMethods = $request->user()->profile()->first()->payment_methods;
        $formattedPaymentMethods = collect($paymentMethods)->map(function ($paymentMethod, $cardType) {
            return array_merge(['card_type' => $cardType], $paymentMethod);
        })->values();
        return $this->returnJSON(PaymentMethodResource::collection($formattedPaymentMethods), __('message.data_retrieved', ['item' => __('message.payment_methods')]));
    }

    public function store(PaymentMethodStoreRequest $request)
    {
        $requestData = $request->validated();
        $cardsCollection = collect($requestData['card_type'])->map(function ($cardType, $index) use ($requestData) {
            return [
                $cardType => [
                    'cardholder_name'  => $requestData['cardholder_name'][$index],
                    'card_number'      => $requestData['card_number'][$index],
                    'expiration_month' => $requestData['expiration_month'][$index],
                    'expiration_year'  => $requestData['expiration_year'][$index],
                    'cvv'              => $requestData['cvv'][$index],
                    'billing_address'  => $requestData['billing_address'][$index],
                ],
            ];
        });
        $request->user()->profile()->update([
            'payment_methods' => $cardsCollection->collapse()
        ]);
        return $this->returnSuccess(__('message.data_added', ['item' => __('message.payment_method')]));
    }
}
