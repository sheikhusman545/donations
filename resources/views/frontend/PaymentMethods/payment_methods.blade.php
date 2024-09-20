@php
    $cardknoxIfieldsKey = '';
    $cardknoxSoftwareName = '';
    $stripePublishableKey = '';
    $paypalEnv = '';

    $authnetPublicKey = '';
    $authnetLoginId = '';
    $authnetApiKey = '';

    $banquestTokenizationSourceKey = '';
    $usaepayPublicKey = '';

    $donorFundApiKey = '';
    $donorFundApiToken = '';
    $donorFundAccountNumber = '';

    $ojcFundApiKey = '';

    $ojcFundUsername = '';
    $ojcFundPassword = '';

@endphp
@if ($payment_gateways->count() > 0)
    <!-- Nav tabs -->

    <ul class="nav payment_tabs_checkbox nav-tabs" id="myTab" role="tablist">
        @foreach ($payment_gateways as $index => $gateway)
            @if ($gateway->payment_method == 'cardknox_cc' || $gateway->payment_method == 'cardknox_ach')
                @if (!isset($cardknoxAdded))
                    @php
                        $cardknoxIfieldsKey = $gateway->public_key;
                        $cardknoxSoftwareName = $gateway->pin;
                    @endphp
                    <li class="nav-item" role="presentation">
                        <button class="nav-link @if ($index == 0) active @endif" id="nav-cardknox-tab"
                            data-bs-toggle="tab" data-bs-target="#tab_cardknox" type="button" role="tab"
                            aria-controls="tab_cardknox" aria-selected="{{ $index == 0 ? 'true' : 'false' }}">
                            <label class="checkbox-container d-flex">
                                <input type="radio" name="payment_gateway" value="cardknox"
                                    @if ($index == 0) checked @endif>
                                <i class="fas fa-landmark"></i>
                                <p>Cardknox</p>
                                <span class="checkbox-checkmark"></span>
                            </label>
                        </button>
                    </li>
                    @php $cardknoxAdded = true; @endphp
                @endif
            @else
                <li class="nav-item" role="presentation">
                    <button class="nav-link @if ($index == 0 && !isset($cardknoxAdded)) active @endif"
                        id="nav-{{ strtolower($gateway->payment_method) }}-tab" data-bs-toggle="tab"
                        data-bs-target="#tab_{{ $index + 1 }}" type="button" role="tab"
                        aria-controls="tab_{{ $index + 1 }}"
                        aria-selected="{{ $index == 0 && !isset($cardknoxAdded) ? 'true' : 'false' }}">
                        <label class="checkbox-container d-flex">
                            <input type="radio" name="payment_gateway" value="{{ $gateway->payment_method }}"
                                @if ($index == 0 && !isset($cardknoxAdded)) checked @endif>
                            @switch($gateway->payment_method)
                                @case('stripe')
                                    <i class="fab fa fas fa-cc-stripe"></i>
                                    <p>Credit Card</p>
                                    @php
                                        $stripePublishableKey = $gateway->public_key;
                                    @endphp
                                @break

                                @case('authorize_net')
                                    <i class="fab fa fas fa-cc-mastercard"></i>
                                    <p>Authorize.net</p>
                                    @php
                                        $authnetPublicKey = $gateway->public_key;
                                        $authnetLoginId = $gateway->pin;
                                        $authnetApiKey = $gateway->api_key;
                                    @endphp
                                @break

                                @case('banquest')
                                    <i class="fab fa fas fa-cc-mastercard"></i>
                                    <p>Banquest</p>
                                    @php
                                        $banquestTokenizationSourceKey = $gateway->public_key;
                                    @endphp
                                @break

                                @case('matbia')
                                    <i class="fas fa fab fa-credit-card"></i>
                                    <p>Matbia Card</p>
                                @break

                                @case('ojc_fund')
                                    <i class="fas fa fab fa-gift"></i>
                                    <p>OJC Card</p>
                                    @php
                                        $ojcFundApiKey = $gateway->api_key;
                                        $ojcFundUsername = $gateway->public_key;
                                        $ojcFundPassword = $gateway->pin;
                                    @endphp
                                @break

                                @case('donors_fund')
                                    <i class="fas fa fab fa-recycle"></i>
                                    <p>Donors Fund</p>
                                    @php
                                        $donorFundApiKey = $gateway->api_key;
                                        $donorFundApiToken = $gateway->public_key;
                                        $donorFundAccountNumber = $gateway->pin;
                                    @endphp
                                @break

                                @case('usaepay')
                                    <i class="fas fa fab fa-credit-card"></i>
                                    <p>USAePay</p>
                                    @php
                                        $usaepayPublicKey = $gateway->public_key;
                                    @endphp
                                @break

                                @case('paypal')
                                    <i class="fab fa fas fa-cc-paypal"></i>
                                    <p>PayPal</p>
                                @break

                                @default
                                    {{-- <i class="fas fab fa fa-credit-card"></i> --}}
                            @endswitch
                            <span class="checkbox-checkmark"></span>
                        </label>
                    </button>
                </li>
            @endif
        @endforeach
    </ul>

    <div class="tab-content mt-5" id="myTabContent">
        @foreach ($payment_gateways as $index => $gateway)
            @if ($gateway->payment_method == 'cardknox_cc' || $gateway->payment_method == 'cardknox_ach')
                @if (!isset($cardknoxPaneAdded))
                    <div class="tab-pane fade @if ($index == 0) show active @endif" id="tab_cardknox"
                        role="tabpanel" aria-labelledby="nav-cardknox-tab">
                        @include('frontend.PaymentMethods.cardknox')
                    </div>
                    @php $cardknoxPaneAdded = true; @endphp
                @endif
            @else
                <div class="tab-pane fade @if ($index == 0 && !isset($cardknoxPaneAdded)) show active @endif"
                    id="tab_{{ $index + 1 }}" role="tabpanel" aria-labelledby="tab_{{ $index + 1 }}-tab">
                    @include('frontend.PaymentMethods.' . strtolower($gateway->payment_method))
                </div>
            @endif
        @endforeach
    </div>
@endif

@php

    $cardknox = [
        'cardknoxIfieldsKey' => $cardknoxIfieldsKey,
        'cardknoxSoftwareName' => $cardknoxSoftwareName,
        'stripePublishableKey' => $stripePublishableKey,
    ];
    $stripe = [
        'stripePublishableKey' => $stripePublishableKey,
    ];
    $authorize = [
        'authnetPublicKey' => $authnetPublicKey,
        'authnetLoginId' => $authnetLoginId,
        'authnetApiKey' => $authnetApiKey,
    ];
    $banquest = [
        'banquestTokenizationSourceKey' => $banquestTokenizationSourceKey,
    ];
    $usaepay = [
        'usaepayPublicKey' => $usaepayPublicKey,
    ];
    $donor_fund = [
        'donorFundApiKey' => $donorFundApiKey,
        'donorFundApiToken' => $donorFundApiToken,
        'donorFundAccountNumber' => $donorFundAccountNumber,
    ];
    $ojc_fund = [
        'ojcFundApiKey' => $ojcFundApiKey,
        'ojcFundUsername' => $ojcFundUsername,
        'ojcFundPassword' => $ojcFundPassword,
    ];
    $paypal = [];

@endphp
@include('frontend.PaymentMethods.payment_call', [
    'cardknox' => $cardknox,
    'authorize' => $authorize,
    'banquest' => $banquest,
    'usaepay' => $usaepay,
    'paypal' => $paypal,
    'stripe' => $stripe,
    'donor_fund' => $donor_fund,
    'ojc_fund' => $ojc_fund,
])
