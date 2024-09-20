@php
$authReq = config('app.payment_env') == 'live' ? 'https://js.authorize.net' : 'https://jstest.authorize.net';
$ueaReq = config('app.payment_env') == 'live' ? 'https://www.usaepay.com' : 'https://sandbox.usaepay.com';
$PP_Rec_EP =
config('app.payment_env') == 'live'
? 'https://www.paypal.com/cgi-bin/webscr'
: 'https://www.sandbox.paypal.com/cgi-bin/webscr';
$banquestReq =
config('app.payment_env') == 'live'
? 'https://tokenization.banquestgateway.com/tokenization/v0.2'
: 'https://tokenization.sandbox.banquestgateway.com/tokenization/v0.2';

@endphp

<script src="https://cdn.cardknox.com/ifields/2.6.2006.0102/ifields.min.js"></script>
<script src="https://js.stripe.com/v3/"></script>
@if ($paypalEnv)
<script src="//www.paypalobjects.com/api/checkout.js"></script>
@endif
@if ($usaepay['usaepayPublicKey'] != '')
<script src="{{ $ueaReq }}/js/v1/pay.js"></script>
@endif
@if ($authorize['authnetPublicKey'] != '')
<script src="{{ $authReq }}/v1/Accept.js"></script>
@endif
@if ($banquest['banquestTokenizationSourceKey'] != '')
<script src="{{ $banquestReq }}"></script>
@endif


<script>
    document.addEventListener("DOMContentLoaded", function(event) {
        const stripePublishableKey = '{{ $stripe['stripePublishableKey'] }}';
        const cardknoxIfieldsKey = '{{ $cardknox['cardknoxIfieldsKey'] }}';
        // alert(cardknoxIfieldsKey);
        const cardknoxSoftwareName = '{{ $cardknox['cardknoxSoftwareName'] }}';
        const paypalEnv = '{{ $paypalEnv }}';
        const authnetPublicKey = '{{ $authorize['authnetPublicKey'] }}';
        const authnetLoginId = '{{ $authorize['authnetLoginId'] }}';
        const authnetApiKey = '{{ $authorize['authnetApiKey'] }}';
        const banquestTokenizationSourceKey = '{{ $banquest['banquestTokenizationSourceKey'] }}';
        const usaepayPublicKey = '{{ $usaepay['usaepayPublicKey'] }}';

        const donorFundApiKey = '{{ $donor_fund['donorFundApiKey'] }}';
        const donorFundApiToken = '{{ $donor_fund['donorFundApiToken'] }}';
        const donorFundAccountNumber = '{{ $donor_fund['donorFundAccountNumber'] }}';

        const ojcFundApiKey = '{{ $ojc_fund['ojcFundApiKey'] }}';
        const ojcFundUsername = '{{ $ojc_fund['ojcFundUsername'] }}';
        const ojcFundPassword = '{{ $ojc_fund['ojcFundPassword'] }}';





        const defaultStyle = {
            'width': 'calc(100% - 64px)'
            , 'height': '33px'
            , 'font-size': '17px'
            , 'font-weight': '600'
            , 'color': '#333'
            , 'background-color': '#fafafa'
            , 'border': '2px solid'
            , 'border-color': '#ccc'
            , 'border-radius': 'calc(infinity * 1px)'
            , 'outline': '0'
            , 'padding': '10px 30px'
        };

        function setRequiredFields(status, method = '') {
            const fields = {
                "xName": false
                , "authnet_card_num": false
                , "authnet_cvc": false
                , "authnet_exp": false
                , "authnet_zip": false
                , "expiryCC": false
                , "xrouting": false
                , "xAchName": false
                , "ojc_card_num": false
                , "ojc_expiry": false
                , "mtb_card_num": false
                , "mtb_expiry": false
                , "dfd_card_num": false
                , "dfd_cvc": false
                , "plg_card_num": false
                , "plg_expiry": false
                , "plg_cvv": false
            };

            for (const field in fields) {
                $("#" + field).attr("required", fields[field]);
            }

            if (method === 'banquest') {
                $("#card").attr("required", status);
                $("#expiry-month").attr("required", status);
                $("#expiry-year").attr("required", status);
                $("#cvv2").attr("required", status);
            } else if (method === 'cardknox') {
                $("#xName").attr("required", status);
                $("#expiryCC").attr("required", status);
                $("#xrouting").attr("required", status);
                $("#xAchName").attr("required", status);
            } else if (method === 'stripe') {
                $("#card-element").attr("required", status);
            } else if (method === 'authorize_net') {
                $("#authnet_card_num").attr("required", status);
                $("#authnet_cvc").attr("required", status);
                $("#authnet_exp").attr("required", status);
                $("#authnet_zip").attr("required", status);
            } else if (method === 'usaepay') {
                $("#uepcc_card_num").attr("required", status);
                $("#uepcc_expiry").attr("required", status);
                $("#uepcc_cvc").attr("required", status);
            } else if (method === 'ojc_fund') {
                $("#ojc_card_num").attr("required", status);
                $("#ojc_expiry").attr("required", status);
            } else if (method === 'paypal') {
                $("#pp_email").attr("required", status);
            } else if (method === 'matbia') {
                $("#mtb_card_num").attr("required", status);
                $("#mtb_expiry").attr("required", status);
            } else if (method === 'donor_fund') {
                $("#dfd_card_num").attr("required", status);
                $("#dfd_cvc").attr("required", status);
            } else if (method === 'pledger') {
                $("#plg_card_num").attr("required", status);
                $("#plg_expiry").attr("required", status);
                $("#plg_cvv").attr("required", status);
            }
        }

        $("#nav-cheque-tab").click(function() {
            $("#pay_with").val("cheque");
            $("#payment_method").val("other");
            setRequiredFields(false);
            $(".payment-tab").hide();
        });

        $("#nav-banquest-tab").click(function() {
            $("#pay_with").val("banquest");
            $("#payment_method").val("banquest");
            setRequiredFields(true, 'banquest');
            $(".payment-tab").hide();
            $(".banquest").show();
        });

        $("#nav-cardknox-tab").click(function() {
            $("#pay_with").val("cardknox");
            $("#payment_method").val("cardknox");
            setRequiredFields(true, 'cardknox');
            $(".payment-tab").hide();
            $(".ckcc_section, .ckach_section").show();
        });

        $("#nav-authorize_net-tab").click(function() {
            $("#pay_with").val("authorize_net");
            $("#payment_method").val("authorize_net");
            setRequiredFields(true, 'authorize_net');
            $(".payment-tab").hide();
            $(".authnet_section").show();
        });

        $("#nav-ojc-tab").click(function() {
            $("#pay_with").val("ojc");
            $("#payment_method").val("ojc_fund");
            setRequiredFields(true, 'ojc_fund');
            $(".payment-tab").hide();
            $(".ojc_section").show();
        });

        $("#nav-pp-tab").click(function() {
            $("#pay_with").val("pp");
            $("#payment_method").val("paypal");
            setRequiredFields(true, 'paypal');
            $(".payment-tab").hide();
            $(".pp_section").show();
        });

        $("#nav-usaepay-tab").click(function() {
            $("#pay_with").val("usaepay");
            $("#payment_method").val("usaepay");
            setRequiredFields(true, 'usaepay');
            $(".payment-tab").hide();
            $(".uepcc_section").show();
        });

        $("#nav-stripe-tab").click(function() {
            $("#pay_with").val("stripe");
            $("#payment_method").val("stripe");
            setRequiredFields(true, 'stripe');
            $(".payment-tab").hide();
            $(".stripe_section").show();
        });

        $("#nav-matbia-tab").click(function() {
            $("#pay_with").val("matbia");
            $("#payment_method").val("matbia");
            setRequiredFields(true, 'matbia');
            $(".payment-tab").hide();
            $(".matbia_section").show();
        });

        $("#nav-donfund-tab").click(function() {
            $("#pay_with").val("donfund");
            $("#payment_method").val("donor_fund");
            setRequiredFields(true, 'donor_fund');
            $(".payment-tab").hide();
            $(".donfund_section").show();
        });

        $("#nav-pledger-tab").click(function() {
            $("#pay_with").val("pledger");
            setRequiredFields(true, 'pledger');
            $(".payment-tab").hide();
            $(".pledger_section").show();
        });






        let stripe, card
        if (stripePublishableKey) {
            stripe = Stripe(stripePublishableKey);
            let elements = stripe.elements();
            const style = {
                base: {
                    color: '#32325d'
                    , lineHeight: '18px'
                    , fontFamily: '"Helvetica Neue", Helvetica, sans-serif'
                    , fontSmoothing: 'antialiased'
                    , fontSize: '16px'
                    , '::placeholder': {
                        color: '#aab7c4'
                    }
                }
                , invalid: {
                    color: '#fa755a'
                    , iconColor: '#fa755a'
                }
            };

            card = elements.create('card', {
                style: style
            });
            card.mount('#card-element');

            card.addEventListener('change', function(event) {
                const displayError = document.getElementById('card-errors');
                if (event.error) {
                    displayError.textContent = event.error.message;
                } else {
                    displayError.textContent = '';
                }
            });
        }
        let validStyle = {
            border: '1px solid green'
        };

        let invalidStyle = {
            border: '1px solid red'
        };

        if (cardknoxIfieldsKey) {
            const defaultStyle = {
                'width': 'calc(100% - 64px)'
                , 'height': '33px'
                , 'font-size': '17px'
                , 'font-weight': '600'
                , 'color': '#333'
                , 'background-color': '#fafafa'
                , 'border': '2px solid'
                , 'border-color': '#ccc'
                , 'border-radius': 'calc(infinity * 1px)'
                , 'outline': '0'
                , 'padding': '10px 30px'
            };

            enableAutoSubmit('payment-form');
            setIfieldStyle('ach', defaultStyle);
            setIfieldStyle('card-number', defaultStyle);
            setIfieldStyle('cvv', defaultStyle);
            setAccount(cardknoxIfieldsKey, cardknoxSoftwareName, '2.0');
            enableAutoFormatting();

            addIfieldKeyPressCallback(function(data) {
                setIfieldStyle('card-number', data.cardNumberFormattedLength <= 0 ? defaultStyle : data
                    .cardNumberIsValid ? validStyle : invalidStyle);
                if (data.lastIfieldChanged === 'cvv') {
                    setIfieldStyle('cvv', data.issuer === 'unknown' || data.cvvLength <= 0 ?
                        defaultStyle : data.cvvIsValid ? validStyle : invalidStyle);
                } else if (data.lastIfieldChanged === 'card-number') {
                    if (data.issuer === 'unknown' || data.cvvLength <= 0) {
                        setIfieldStyle('cvv', defaultStyle);
                    } else if (data.issuer === 'amex') {
                        setIfieldStyle('cvv', data.cvvLength === 4 ? validStyle : invalidStyle);
                    } else {
                        setIfieldStyle('cvv', data.cvvLength === 3 ? validStyle : invalidStyle);
                    }
                } else if (data.lastIfieldChanged === 'ach') {
                    setIfieldStyle('ach', data.achLength === 0 ? defaultStyle : data.achIsValid ?
                        validStyle : invalidStyle);
                }
            });
        }
        let cardForm;
        if (banquestTokenizationSourceKey) {
            const hostedTokenization = new window.HostedTokenization(banquestTokenizationSourceKey);
            cardForm = hostedTokenization.create('card-form');
            cardForm.mount('#card-form-container');
            cardForm.setStyles({
                container: 'width: 100%; box-sizing: border-box;'
                , card: 'margin-top:8px; width: calc(100% - 65px); font-size: 17px; font-weight: 600; color: var(--secondary-color); background-color: #fafafa; border: 2px solid; border-color: #ccc; border-radius: calc(infinity * 1px); outline: 0; padding: 12px 30px;'
                , expiryContainer: 'display: flex; justify-content: flex-start; align-items: center; width: 100%; box-sizing: border-box; gap: 15px;'
                , expiryMonth: 'width: calc(100% - 65px); margin-top:8px; max-width: 380px; font-size: 17px; font-weight: 600; color: var(--secondary-color); background-color: #fafafa; border: 2px solid; border-color: #ccc; border-radius: calc(infinity * 1px); outline: 0; padding: 12px 30px;'
                , expirySeparator: 'font-size: 16px; padding: 0 5px; display: none;'
                , expiryYear: 'width: calc(100% - 65px); margin-top:8px; max-width: 380px; font-size: 17px; font-weight: 600; color: var(--secondary-color); background-color: #fafafa; border: 2px solid; border-color: #ccc; border-radius: calc(infinity * 1px); outline: 0; padding: 12px 30px;'
                , cvv2: 'margin-top:8px; width: calc(100% - 65px); font-size: 17px; font-weight: 600; color: var(--secondary-color); background-color: #fafafa; border: 2px solid; border-color: #ccc; border-radius: calc(infinity * 1px); outline: 0; padding: 12px 30px;'
            , });
        }

        let submitCount = 0;
        document.getElementById('payment-form').addEventListener('submit', async (e) => {
            e.preventDefault();

            const form = document.getElementById('payment-form');
            // await grecaptcha.execute();
            // const captchaRes = grecaptcha.getResponse();
            let a = true
            // if (a) {
            //     alert("Please verify you are Human!");
            // } else {
            // if (submitCount === 1) {
            //     return;
            // }
            submitCount++;
            const submitBtn = this;
            submitBtn.disabled = true;

            const paymentMethod = $("#pay_with").val();

            if (paymentMethod === 'stripe') {
                stripe.createToken(card).then(function(result) {
                    if (result.error) {
                        alert('Error: ' + result.error.message);
                        const errorElement = document.getElementById('card-errors');
                        errorElement.textContent = result.error.message;
                    } else {

                        const formData = new FormData(form);
                        const formDataObject = {};
                        formData.forEach((value, key) => {
                            formDataObject[key] = value;
                        });
                        formDataObject.stripeToken = result.token.id;
                        fetch(form.action, {
                            method: 'POST'
                            , headers: {
                                'Content-Type': 'application/json'
                                , 'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').getAttribute(
                                    'content')
                            }
                            , body: JSON.stringify(formDataObject)
                        }).then(response => response.json()).then(result => {
                            if (!result.success) {
                                const errorElement = document.getElementById(
                                    'card-errors');
                                errorElement.textContent = result.error;
                                alert("Error: " + result.error);
                            } else {
                                alert('Payment successful!');
                            }
                        });
                    }
                });
            } else if (paymentMethod === 'cardknox') {
                getTokens(function() {
                    const formData = new FormData(form);
                    const formDataObject = {};
                    formData.forEach((value, key) => {
                        formDataObject[key] = value;
                    });
                    fetch(form.action, {
                        method: 'POST'
                        , headers: {
                            'Content-Type': 'application/json'
                            , 'X-CSRF-TOKEN': document.querySelector(
                                'meta[name="csrf-token"]').getAttribute(
                                'content')
                        }
                        , body: JSON.stringify(formDataObject)
                    }).then(response => response.json()).then(data => {
                        if (data.success) {
                            alert('Payment successful!');
                        } else {
                            alert('Payment failed: ' + data.error);
                        }
                    }).catch(error => {
                        console.error('Error:', error);
                        alert('Payment failed: ' + error.message);
                    });
                }, function(error) {
                    console.error('Error:', error);
                    alert('Payment failed: ' + error.message);
                });

            } else if (paymentMethod === 'banquest') {
                try {
                    const result = await cardForm.getNonceToken();
                    if (result && result.error) {
                        alert('Errorr: ' + result.error);
                    } else {
                        const formData = new FormData(form);
                        const formDataObject = {};
                        formData.forEach((value, key) => {
                            formDataObject[key] = value;
                        });
                        formDataObject.banquestData = result;
                        fetch(form.action, {
                            method: 'POST'
                            , headers: {
                                'Content-Type': 'application/json'
                                , 'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').getAttribute(
                                    'content')
                            }
                            , body: JSON.stringify(formDataObject)
                        }).then(response => response.json()).then(data => {
                            if (data.success) {
                                alert('Payment successful!');
                            } else {
                                alert('Payment failed: ' + data.message);
                            }
                        }).catch(error => {
                            console.error('Error:', error);
                            alert('Payment failed: ' + error.message);
                        });
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Payment failed: ' + error.message);
                }
            } else if (paymentMethod == "authorize_net") {
                function responseHandler(response) {
                    console.log("Response:", response);
                    if (response.messages && response.messages.resultCode === "Ok") {
                        var opaqueData = response.opaqueData;
                        console.log('opaqueData', opaqueData.dataValue);
                        var form = document.getElementById('payment-form');
                        var formData = new FormData(form);
                        formData.append('opaqueDataString', opaqueData.dataValue);

                        fetch(form.action, {
                                method: 'POST'
                                , headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                }
                                , body: formData
                            })
                            .then(response => response.json())
                            .then(result => {
                                if (!result.success) {
                                    const errorElement = document.getElementById('card-errors');
                                    errorElement.textContent = result.error;
                                    alert("Error: " + result.error);
                                } else {
                                    alert('Payment successful!');
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                            });

                    } else {
                        alert('Error: ' + response.messages.message[0].text);
                    }
                }

                let cardNumber = document.getElementById("authnet_card_num").value;
                let cardCode = document.getElementById("authnet_cvc").value;
                let inputValue = document.getElementById("authnet_exp").value;

                if (!cardNumber || !cardCode || !inputValue) {
                    console.error('Card details are incomplete.');
                    return;
                }

                let parts = inputValue.split('/');
                if (parts.length !== 2) {
                    console.error('Invalid expiration date format.');
                    return;
                }

                let month = parts[0];
                let year = parts[1];

                var cardData = {
                    cardNumber: cardNumber
                    , month: month
                    , year: year
                    , cardCode: cardCode
                };

                var authData = {
                    clientKey: '523Qp9zrAbnZ6rPb5WWcsRxkga2ZePUS96YA5m8E5jsHmKudd8PGUt4hWh3yB7pt'
                    , apiLoginID: '552ZqqM8'
                };

                var secureData = {
                    authData: authData
                    , cardData: cardData
                };

                Accept.dispatchData(secureData, responseHandler);

            } else if (paymentMethod == "usaepay") {
                const usaePayPublicKey = '{{ $usaepay['usaepayPublicKey'] }}';
         let client = new usaepay.Client(usaePayPublicKey);
         let paymentCard = client.createPaymentCardEntry();
         style = {}
         paymentCard.generateHTML(style);
         paymentCard.addHTML('paymentCardContainer');
         paymentCard.addEventListener('error', errorMessage => {
             let errorContainer = document.getElementById('paymentCardErrorContainer');
             errorContainer.textContent = errorMessage;
         });
             let = cardDetails =  client.getPaymentKey(paymentCard).then(result => {
                    if (result.error) {
                        let errorContainer = document.getElementById('paymentCardErrorContainer');
                        errorContainer.textContent = result.error.message;
                    } else {
                        tokenHandler(result, cardDetails);
                    }
                }).catch(err => {
                    console.log(err);
                });

            function tokenHandler(token, cardDetails) {
                var form = document.getElementById('payment-form');
                var hiddenInput = document.createElement('input');
                hiddenInput.setAttribute('type', 'hidden');
                hiddenInput.setAttribute('name', 'payment_key');
                hiddenInput.setAttribute('value', token);
                form.appendChild(hiddenInput);
                //    console.log(cardDetails);
                //    alert(cardDetails);
                // var serializedData = form.serialize();
                var formData = new FormData(form);
                let url =  document.querySelector('form').getAttribute('action');
                //  alert(url);
                // Submit the form
                  fetch(form.action, {
                         method: 'POST'
                         , headers: {
                             'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                         }
                         , body: formData
                     })
                     .then(response => response.text())
                     .then(text => {
                       console.log(text);
                       try {
                           const result = JSON.parse(text);
                           if (!result.success) {
                               const errorElement = document.getElementById('card-errors');
                               errorElement.textContent = result.error;
                               alert("Error: " + result.error);
                           } else {
                               alert('Payment successful!');
                           }
                       } catch (error) {
                           console.error('Failed to parse JSON:', error);
                       }
                   })
            }
        }

        setTimeout(() => {
            submitCount = 0
        }, 3000);
    });
});

</script>

<script>

</script>
