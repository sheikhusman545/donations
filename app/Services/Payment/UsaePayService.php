<?php

// namespace App\Services\Payment;

// use App\Models\PaymentMethod;
// use Illuminate\Support\Facades\Http;
// // use USAePay\USAePayAPI;
// // use USAePay\Client;
// // new \PhpUsaepay\Client
// use USAePay\API;
// class UsaePayService
// {
//     private $apiKey;
//     private $apiPin;
//     private $endpoint;
//     public function __construct()
//     {
//         $payment_env = config('app.payment_env');
//         $this->endpoint = $payment_env == "live" ? "https://www.usaepay.com" : "https://sandbox.usaepay.com";
//         $this->apiKey = '_2hYkFkM62bulTUi7YBJSyFY2CxLLB6q';
//         $this->apiPin = '1124';
//     }

//     public function processPayment($paymentData)
//     {
//         $client = new API;
//         $client->setAuthentication($this->apiKey,$this->apiPin);
//         $client->setEndpointKey($this->endpoint);
//         // $client = new USAePay($this->apiKey, $this->apiPin);
//         $data = [
//                     'command' => 'cc:sale',
//                     'payment_key' => $paymentData->payment_key,
//                     'amount' => $paymentData->amount,
//                     'currency' => $paymentData->currency,
//                     'amount_detail' => [
//                         'tax' => '0.00',
//                         'subtotal' => $paymentData->amount
//                     ],
//                     'billing_address' => $paymentData->address,
//                     'custemailaddr' => $paymentData->email,
//                 ];
//         try {
//             $response = $client->runCall('post','/api/v2/transactions', $data);
//             dd($response);
//         } catch (\Exception $e) {
//             return [
//                 'success' => false,
//                 'message' => $e->getMessage()
//             ];
//         }
//     }

//     //     $seed = "abcdefghijklmnop";
//     //     $prehash = $this->apiKey . $seed . $this->apiPin;
//     //     $apihash = "s2/" . $seed . "/" . hash("sha256", $prehash);
//     //     $authKey = base64_encode($this->apiKey . ':' . $apihash);
//     //     $headers = [
//     //         'Content-Type: application/json',
//     //         'Authorization: Basic ' . $authKey
//     //     ];
//     //     $data = [
//     //         'command' => 'cc:sale',
//     //         'payment_key' => $paymentKey,
//     //         'amount' => $amount,
//     //         'currency' => $currency,
//     //         'amount_detail' => [
//     //             'tax' => '0.00',
//     //             'subtotal' => $amount
//     //         ],
//     //         'billing_address' => $billingAddress,
//     //         'custemailaddr' => $email,
//     //     ];
//     //     if ($recurring) {
//     //         $data['save_customer'] = true;
//     //         $data['save_customer_paymethod'] = true;
//     //     }
//     //     $ch = curl_init();
//     //     curl_setopt($ch, CURLOPT_URL, $this->endpoint . '/api/v2/transactions');
//     //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//     //     curl_setopt($ch, CURLOPT_POST, 1);
//     //     curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
//     //     curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//     //     $result = curl_exec($ch);
//     //     curl_close($ch);
//     //     $transData = json_decode($result, true);
//     //     if (isset($transData['creditcard'])) {
//     //         $cardNumber = substr($transData['creditcard']['number'], -4);
//     //         $cardFirstFour = substr($transData['creditcard']['number'], 0, 4);
//     //         $cardType = $this->getCardType($cardFirstFour);
//     //         $cardExpiry = $transData['creditcard']['expiration'];
//     //     } else {
//     //         // Handle error or missing data
//     //         $cardNumber = null;
//     //         $cardType = "Unknown";
//     //         $cardExpiry = null;
//     //     }
//     //     return [
//     //         'cardNumber' => $cardNumber,
//     //         'cardType' => $cardType,
//     //         'cardExpiry' => $cardExpiry,
//     //         'transactionData' => $transData
//     //     ];
//     // }
//     // private function getCardType($cardFirstFour)
//     // {
//     //     // Implement card type detection logic based on cardFirstFour digits
//     //     // Example:
//     //     if (preg_match('/^4/', $cardFirstFour)) {
//     //         return 'Visa';
//     //     } elseif (preg_match('/^5[1-5]/', $cardFirstFour)) {
//     //         return 'MasterCard';
//     //     } else {
//     //         return 'Unknown';
//     //     }
//     // }
// }



namespace App\Services\Payment;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Http;
use USAePay\API;

class UsaePayService
{
    private $apiKey;
    private $apiPin;
    private $endpoint;

    public function __construct()
    {
        $payment_env = config('app.payment_env');
        $this->endpoint = $payment_env === "live" ? "secure" : "sandbox";
        $this->apiKey = '_2hYkFkM62bulTUi7YBJSyFY2CxLLB6q';
        $this->apiPin = '1124';
    }

    public function processPayment($paymentData)
    {
        $client = new API();
        $client->setAuthentication($this->apiKey, $this->apiPin);
        $client->setSubdomain($this->endpoint);

        $data = [
            'command' => 'cc:sale',
            'amount' => $paymentData['amount'],
            'currency' => $paymentData['currency'],
            'amount_detail' => [
                'tax' => '0.00',
                'subtotal' => $paymentData['amount'],
            ],
            'billing_address' => $paymentData['address'],
            'custemailaddr' => $paymentData['email'],
        ];

        try {
            $response = $client->runCall('post', '/transactions', $data);
            // return ['success' => true, 'paymentIntent' => $subscription];
            return [
                'success' => true,
                'paymentIntent' => $response
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}
