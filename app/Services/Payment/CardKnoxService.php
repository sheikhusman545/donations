<?php

namespace App\Services\Payment;

use Illuminate\Support\Facades\Http;

class CardknoxService
{
    protected $apiKey;
    protected $softwareName;
    protected $url;

    public function __construct()
    {

        $this->apiKey = 'developmentdev329ae108d0ee4166bbda7b9a5a27048';
        $this->softwareName =  'developmentdev';
        $this->url = "https://x1.cardknox.com/gatewayjson";
    }

    public function processPayment($request)
    {

        $is_recurring = $request->don_recurring;
        $intervalType =  $request->recurring;
        $recurring_type = $intervalType == "Daily"
            ? "Day" : ($intervalType == "Weekly"
                ? "Week" : ($intervalType == "Monthly"
                    ? "Month" : "Year"));
        $total_amount = $request->usd_amount;
        $recurring_intervals = $request->recurring_intervals;

        // if ($is_recurring == 1) {
        //     try {
        //         $this->url = "https://api.cardknox.com/recurring";
        //         switch (strtolower($recurring_type)) {
        //             case 'day':
        //                 $periodType = 'days';
        //                 break;
        //             case 'week':
        //                 $periodType = 'weeks';
        //                 break;
        //             case 'month':
        //                 $periodType = 'months';
        //                 break;
        //             case 'year':
        //                 $periodType = 'years';
        //                 break;
        //             default:
        //                 throw new \Exception('Invalid Recurring Interval');
        //         }
        //         if ($recurring_intervals <= 0) {
        //             throw new \Exception('Installment duration must be greater than zero');
        //         }
        //         $installment_amount = ceil($total_amount / $recurring_intervals);
        //         $response = Http::post($this->url, [
        //             'xKey' => $request->paymentMethodConfig->api_key,
        //             'xSoftwareName' => $this->softwareName,
        //             "xVersion" => "1.0.0",
        //             'xCommand' => 'cc:sale',
        //             'xSoftwareVersion' => '2.0',
        //             'xAmount' => $installment_amount,
        //             'xCardNum' => $request->cardNumber,
        //             'xExp' => $request->expMonth . $request->expYear,
        //             'xCVV' => $request->cvv,
        //             'xRecurring' => '1',
        //             'xRecurringType' => 'fixed',
        //             'xRecurringAmount' => $installment_amount,
        //             'xRecurringPeriod' => $recurring_intervals,
        //             'xRecurringPeriodType' => $periodType,
        //             'xRecurringStartDate' => date('Ymd'),
        //             'xRecurringEndDate' => date('Ymd', strtotime("+$recurring_intervals $periodType")),
        //         ]);
        //         return ['success' => true, 'paymentIntent' => $response];
        //     } catch (\Exception $e) {
        //         return ['success' => false, 'error' => $e->getMessage()];
        //     }
        // }
        try {
            $response = Http::post($this->url, [
                'xKey' => $request->paymentMethodConfig->api_key,
                'xSoftwareName' => $this->softwareName,
                'xVersion' => '4.5.9',
                'xCommand' => 'cc:sale',
                'xSoftwareVersion' => '2.0',
                'xAmount' => $request->amount,
                'xCardNum' => $request->xCardNum,
                'xExp' => '1244',
                'xCVV' => $request->xCVV,
                'xName' => $request->xName,
            ]);

            if (empty($response['xError'])) {
                return ['success' => true, 'paymentIntent' => $response];
            } else {
                throw new \Exception($response['xError']);
            }
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
