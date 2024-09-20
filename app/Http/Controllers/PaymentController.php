<?php

namespace App\Http\Controllers;

use App\Services\Payment\PaymentProcessor;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;


class PaymentController extends Controller
{

    protected $paymentProcessor;
    protected $donationController;
    public function __construct(PaymentProcessor $paymentProcessor, donationController  $donationController)
    {
        $this->paymentProcessor = $paymentProcessor;
        $this->donationController = $donationController;
    }


    public function processPayment(Request $request, $campaignId)
    {


        $request->validate([
            'amount' => 'required|numeric|min:1',
            'pay_with' => 'required|string',
        ]);

        try {
            $paymentIntent = $this->paymentProcessor->createPaymentIntent($request, $campaignId);


            // if ($paymentIntent['success'] == false) {
            //     return  $paymentIntent;
            // }
            if ($paymentIntent instanceof JsonResponse) {
                $paymentIntentArray = $paymentIntent->getData(true); 
                if (isset($paymentIntentArray['success']) && $paymentIntentArray['success'] == false) {
                    return $paymentIntent;
                }
            } elseif (is_array($paymentIntent) && isset($paymentIntent['success']) && $paymentIntent['success'] == false) {
                return $paymentIntent;
            }

            $donation =  $this->donationController->handlePostPayment($request, $campaignId, $paymentIntent);


            return response()->json(['success' => 'Payment successful', 'donation' => $donation], 200);


            $paymentMethod = $request->input('pay_with');


            return $paymentIntent;

            if ($paymentMethod == 'cardknox' && $paymentIntent['xResult'] != 'A') {
                return response()->json(['error' => $paymentIntent], 400);
            } else {
                return response()->json(['success' => 'Payment successful'], 200);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
        // return response()->json(['client_secret' => $paymentIntent->client_secret]);
    }
}
