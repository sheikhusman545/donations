<?php

namespace App\Services\Payment;

use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Source;

class StripeService
{
    public function createPaymentIntent($request)
    {


        Stripe::setApiKey($request->paymentMethodConfig->api_key);

        $source = Source::create([
            'type' => 'card',
            'token' => $request->stripeToken,
        ]);

        try {
            $is_recurring = $request->don_recurring;
            $intervalType = $request->recurring;
            $recurring_interval = $intervalType == "Daily"
                ? "Day" : ($intervalType == "Weekly"
                    ? "Week" : ($intervalType == "Monthly"
                        ? "Month" : "Year"));
            $total_amount = $request->usd_amount;
            $recurring_intervals = $request->recurring_intervals;

            if ($is_recurring == 1) {
                $customer = \Stripe\Customer::create([
                    'email' => $request->donor_email,
                    'name' => $request->donor_first_name . ' ' . $request->donor_last_name,
                    'source' => $source->id,
                    'phone' => $request->donor_phone,
                    'address' => [
                        'line1' => $request->address,
                        'city' => $request->city,
                        'state' => $request->state,
                        'country' => $request->country,
                        'postal_code' => $request->zipcode,
                    ],
                ]);
                // $installment_amount = 0;
                // $installment_cycles = 0;
                switch (strtolower($recurring_interval)) {
                    case 'day':
                        $installment_cycles = $recurring_intervals;
                        break;
                    case 'week':
                        $installment_cycles = $recurring_intervals;
                        break;
                    case 'month':
                        $installment_cycles = $recurring_intervals;
                        break;
                    default:
                        throw new \Exception('Invalid Recurring Intervels');
                }
                if ($installment_cycles <= 0) {
                    throw new \Exception('Installment duration must be greater than zero');
                }
                $installment_amount = ceil($total_amount / $installment_cycles);

                $plan = \Stripe\Plan::create([
                    'amount' => $installment_amount * 100,
                    'currency' => $request->currency,
                    'interval' => strtolower($recurring_interval),
                    'product' => [
                        'name' =>  $recurring_interval . ' Installment Plan'
                    ],
                    'nickname' => 'Installment Payment Plan for ' . $request->donor_first_name
                ]);

                $subscription = \Stripe\Subscription::create([
                    'customer' => $customer->id,
                    'items' => [
                        ['plan' => $plan->id]
                    ],
                    'default_payment_method' => $source->id,
                    'billing_cycle_anchor' => time() + 60,
                    'metadata' => [
                        'total_amount' => $total_amount,
                        'installment_cycles' => $installment_cycles,
                        'recurring_interval' => strtolower($recurring_interval)
                    ],
                ]);
                return ['success' => true, 'paymentIntent' => $subscription];
            } else {
                $paymentIntent = PaymentIntent::create([
                    "amount" => $request->amount * 100,
                    "currency" => "usd",
                    "source" => $source->id,
                    "description" => "Stripe Test Payment",
                    'payment_method_types' => ['card'],
                    'off_session' => true,
                    'confirmation_method' => 'automatic',
                    'confirm' => true
                ]);
                $paymentIntent = [
                    'client_secret' => $paymentIntent->client_secret,
                    'id' => $paymentIntent->id,
                    'status' => $paymentIntent->status,
                    'amount' => $paymentIntent->amount,
                    'currency' => $paymentIntent->currency,
                    'created' => $paymentIntent->created,
                    'description' => $paymentIntent->description,
                    'payment_method' => $paymentIntent->payment_method,
                    'receipt_email' => $paymentIntent->receipt_email,
                    'source' => $paymentIntent->source,
                    'status' => $paymentIntent->status,
                    'payment_method_types' => $paymentIntent->payment_method_types,
                    'off_session' => $paymentIntent->off_session,
                    'confirmation_method' => $paymentIntent->confirmation_method,
                    'metadata' => $paymentIntent->metadata,
                    'next_action' => $paymentIntent->next_action,
                    'charges' => $paymentIntent->charges,
                    'canceled_at' => $paymentIntent->canceled_at,
                    'cancellation_reason' => $paymentIntent->cancellation_reason,
                    'client_secret' => $paymentIntent->client_secret,
                    'created' => $paymentIntent->created,
                    'customer' => $paymentIntent->customer,
                    'livemode' => $paymentIntent->livemode,
                    'next_action' => $paymentIntent->next_action,
                    'payment_method' => $paymentIntent->payment_method,
                    'payment_method_types' => $paymentIntent->payment_method_types,
                    'receipt_email' => $paymentIntent->receipt_email,
                    'setup_future_usage' => $paymentIntent->setup_future_usage,
                    'shipping' => $paymentIntent->shipping,
                    'source' => $paymentIntent->source,
                    'status' => $paymentIntent->status,
                    'transfer_data' => $paymentIntent->transfer_data,
                    'transfer_group' => $paymentIntent->transfer_group,
                ];


                return ['success' => true, 'paymentIntent' => $paymentIntent];
            }
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
