// function validateNum(evt) {
//   var theEvent = evt || window.event;
//   var key = theEvent.keyCode || theEvent.which;
//   key = String.fromCharCode(key);
//   var regex = /[0-9]/;
//   if (!regex.test(key)) {
//     theEvent.returnValue = false;
//     if (theEvent.preventDefault) theEvent.preventDefault();
//   }
// }

// function validateAmount(evt) {
//   var theEvent = evt || window.event;
//   var key = theEvent.keyCode || theEvent.which;
//   key = String.fromCharCode(key);
//   var regex = /[0-9]|\./;
//   if (!regex.test(key)) {
//     theEvent.returnValue = false;
//     if (theEvent.preventDefault) theEvent.preventDefault();
//   }
// }

// function validateLet(evt) {
//   var theEvent = evt || window.event;
//   var key = theEvent.keyCode || theEvent.which;
//   key = String.fromCharCode(key);
//   var regex = /[A-Za-z]/;
//   if (!regex.test(key)) {
//     theEvent.returnValue = false;
//     if (theEvent.preventDefault) theEvent.preventDefault();
//   }
// }

// function validatePhone(evt) {
//   var theEvent = evt || window.event;
//   var key = theEvent.keyCode || theEvent.which;
//   key = String.fromCharCode(key);
//   var regex = /[0-9+)( -]|\./;
//   if (!regex.test(key)) {
//     theEvent.returnValue = false;
//     if (theEvent.preventDefault) theEvent.preventDefault();
//   }
// }

// function validatePhoneSystemNum(evt) {
//   var theEvent = evt || window.event;
//   var key = theEvent.keyCode || theEvent.which;
//   key = String.fromCharCode(key);
//   var regex = /^[0-9]{0,10}$/;

//   if (!regex.test(key)) {
//     theEvent.returnValue = false;
//     if (theEvent.preventDefault) theEvent.preventDefault();
//   }
// }

// function formatString(e) {
//   var inputChar = String.fromCharCode(event.keyCode);
//   var code = event.keyCode;
//   var allowedKeys = [8];
//   if (allowedKeys.indexOf(code) !== -1) {
//     return;
//   }
//   event.target.value = event.target.value
//     .replace(
//       /^([1-9]\/|[2-9])$/g,
//       "0$1/" // 3 > 03/
//     )
//     .replace(
//       /^(0[1-9]|1[0-2])$/g,
//       "$1/" // 11 > 11/
//     )
//     .replace(
//       /^([0-1])([3-9])$/g,
//       "0$1/$2" // 13 > 01/3
//     )
//     .replace(
//       /^(0?[1-9]|1[0-2])([0-9]{2})$/g,
//       "$1/$2" // 141 > 01/41
//     )
//     .replace(
//       /^([0]+)\/|[0]+$/g,
//       "0" // 0/ > 0 and 00 > 0
//     )
//     .replace(
//       /[^\d\/]|^[\/]*$/g,
//       "" // To allow only digits and `/`
//     )
//     .replace(
//       /\/\//g,
//       "/" // Prevent entering more than 1 `/`
//     );
// }
// $("#currency-select").on("change", function () {
//   sessionStorage.setItem("fromCheckout", "no");
//   var toCurrency = $("#currency-select").val();
//   var baseURL = $("#baseURL").val();
//   updateSessionCurrency(baseURL, toCurrency, true, false);
// });

// $("#donate_currency").on("change", function () {
//   sessionStorage.setItem("fromCheckout", "yes");

//   var toCurrency = $("#donate_currency option:selected").val();
//   var usdAmount = $("#usd_amount").val();
//   var baseURL = $("#baseURL").val();
//   var currency = $("#donate_currency option:selected")
//     .text()
//     .replace(" (" + toCurrency + ")", "");

//   $("#overlayloader").css("display", "block");

//   $.post(
//     baseURL + "/actions/donation-actions.php",
//     {
//       userAction: "getAmountRate",
//       usdAmount: usdAmount,
//       toCurrency: toCurrency,
//     },
//     function (data) {
//       $("#overlayloader").css("display", "none");
//       $("#donate_amount").val(data);
//       if ($("#donate_amount").val() > 0) {
//         var proFeeAmt = $("#fee_percentage").val();
//         var processFee = ($("#donate_amount").val() * proFeeAmt) / 100;
//         $("#process_fee").html(
//           "Add " +
//             currency +
//             processFee.toFixed(2) +
//             " to your payment to cover the " +
//             proFeeAmt +
//             "% credit card processing fee"
//         );
//         $("#don_process_fee").val(processFee.toFixed(2));
//         var recInterval = $("#recurring_cycle option:selected").val();
//         var recMonthAmt = $("#donate_amount").val() / recInterval;
//         $("#rec_amount_full").html(currency + $("#donate_amount").val());
//         showPaymentSection();
//         updateSessionCurrency(baseURL, toCurrency, true);
//       } else {
//         $("#process_fee").html("Cover credit card processing fee");
//         $("#don_process_fee").val(0);
//         $("#rec_amount_full").html(currency + "0.00");
//       }
//     }
//   );
// });
// function updateSessionCurrency(baseURL, newCurrency, isManuallySet) {
//   $.post(
//     baseURL + "/actions/donation-actions.php",
//     {
//   userAction: "setCurrencySession",
//       currency: newCurrency,
//       manuallySet: isManuallySet ? 1 : 0,
//     },
//     function (response) {
//       location.reload();
//     }
//   );
// }
// $("#donate_amount").keyup(function () {
//   if (this.value > 0) {
//     var proFeeAmt = $("#fee_percentage").val();
//     var processFee = (this.value * proFeeAmt) / 100;
//     var currency = $("#donate_currency option:selected").text();
//     $("#process_fee").html(
//       "Add " +
//         currency +
//         processFee.toFixed(2) +
//         " to your payment to cover the " +
//         proFeeAmt +
//         "% credit card processing fee"
//     );
//     $("#don_process_fee").val(processFee.toFixed(2));

//     var isRecPay = $("#don_recurring").is(":checked") ? 1 : 0;
//     $("#isRec").val(isRecPay);

//     if ($("#isRec").val() == "1") {
//       var recInterval = $("#recurring_cycle option:selected").val();
//       var recMonthAmt = this.value / recInterval;
//       $("#rec_amount_full").html(currency + this.value);
//       $("#rec_amount_month").html(currency + recMonthAmt.toFixed(2));
//     }
//     // $(".currency-code").html("$");
//     // $(".currency-symbol").html("USD");
//     // $("#donate_currency option[value=USD]").attr("selected", "selected");

//     showPaymentSection();
//   } else {
//     $("#process_fee").html("Cover credit card processing fee");
//     $("#don_process_fee").val(0);
//     if ($("#isRec").val() == "1") {
//       $("#rec_amount_full").html(currency + "0.00");
//     }
//     $("#paymentMethods").html("");
//   }
// });

// function makeRecurring() {
//   if ($("#don_recurring").is(":checked")) {
//     $(".rec_btns").show();
//     $("#isRec").val("1");
//   } else {
//     $(".rec_btns").hide();
//     $("#don_recurring").removeAttr("checked");
//     $("#custom_recurring_cycle").val("2");
//     $(".default_radio").trigger("click");
//     $("#isRec").val("0");
//   }
// //   showPaymentSection();
// }

// // $("#custom_recurring_cycle").bind("keyup mouseup", function () {
// //   if (this.value > 0) {
// //     showPaymentSection();
// //   }
// // });

// // $("#process_fee_check").click(function () {
// //   showPaymentSection();
// // });

// $("#nav-cheque-tab").click(function () {
//   $("#pay_with").val("cheque");
//   $("#payment_method").val("other");
//   $("#xName").attr("required", false);
//   $("#authnet_card_num").attr("required", false);
//   $("#authnet_cvc").attr("required", false);
//   $("#authnet_exp").attr("required", false);
//   $("#authnet_zip").attr("required", false);
//   $("#expiryCC").attr("required", false);
//   $("#xrouting").attr("required", false);
//   $("#xAchName").attr("required", false);
//   $("#ojc_card_num").attr("required", false);
//   $("#ojc_expiry").attr("required", false);
//   $("#mtb_card_num").attr("required", false);
//   $("#mtb_expiry").attr("required", false);
//   $("#dfd_card_num").attr("required", false);
//   $("#dfd_cvc").attr("required", false);
//   $("#plg_card_num").attr("required", false);
//   $("#plg_expiry").attr("required", false);
//   $("#plg_cvv").attr("required", false);
//   $(".payment-tab").hide();
// });

// $("#nav-bankquest-tab").click(function () {
//   $("#pay_with").val("bankquest");
//   $("#payment_method").val("banquest");
//   $("#card").attr("required", true);
//   $("#expiry-month").attr("required", true);
//   $("#expiry-year").attr("required", true);
//   $("#cvv2").attr("required", true);
//   $("#xName").attr("required", false);
//   $("#authnet_card_num").attr("required", false);
//   $("#authnet_cvc").attr("required", false);
//   $("#authnet_exp").attr("required", false);
//   $("#authnet_zip").attr("required", false);
//   $("#expiryCC").attr("required", false);
//   $("#xrouting").attr("required", false);
//   $("#xAchName").attr("required", false);
//   $("#ojc_card_num").attr("required", false);
//   $("#ojc_expiry").attr("required", false);
//   $("#mtb_card_num").attr("required", false);
//   $("#mtb_expiry").attr("required", false);
//   $("#dfd_card_num").attr("required", false);
//   $("#dfd_cvc").attr("required", false);
//   $("#plg_card_num").attr("required", false);
//   $("#plg_expiry").attr("required", false);
//   $("#plg_cvv").attr("required", false);
//   $(".payment-tab").hide();
//   $(".bankquest").show();
// });
// $("#nav-authnet-tab").click(function () {
//   $("#pay_with").val("authnet");
//   $("#payment_method").val("authorize_net");
//   $("#xName").attr("required", false);
//   $("#authnet_card_num").attr("required", true);
//   $("#authnet_cvc").attr("required", true);
//   $("#authnet_exp").attr("required", true);
//   $("#authnet_zip").attr("required", true);
//   $("#expiryCC").attr("required", false);
//   $("#xrouting").attr("required", false);
//   $("#xAchName").attr("required", false);
//   $("#ojc_card_num").attr("required", false);
//   $("#ojc_expiry").attr("required", false);
//   $("#mtb_card_num").attr("required", false);
//   $("#mtb_expiry").attr("required", false);
//   $("#dfd_card_num").attr("required", false);
//   $("#dfd_cvc").attr("required", false);
//   $("#plg_card_num").attr("required", false);
//   $("#plg_expiry").attr("required", false);
//   $("#plg_cvv").attr("required", false);
//   $(".payment-tab").hide();
//   $(".authnet_section").show();
// });
// $("#nav-ckcc-tab").click(function () {
//   $("#pay_with").val("ckcc");
//   $("#payment_method").val("cardknox");
//   $("#xName").attr("required", true);
//   $("#authnet_card_num").attr("required", false);
//   $("#authnet_cvc").attr("required", false);
//   $("#authnet_exp").attr("required", false);
//   $("#authnet_zip").attr("required", false);
//   $("#expiryCC").attr("required", true);
//   $("#xrouting").attr("required", false);
//   $("#xAchName").attr("required", false);
//   $("#ojc_card_num").attr("required", false);
//   $("#ojc_expiry").attr("required", false);
//   $("#mtb_card_num").attr("required", false);
//   $("#mtb_expiry").attr("required", false);
//   $("#dfd_card_num").attr("required", false);
//   $("#dfd_cvc").attr("required", false);
//   $("#plg_card_num").attr("required", false);
//   $("#plg_expiry").attr("required", false);
//   $("#plg_cvv").attr("required", false);
//   $(".payment-tab").hide();
//   $(".ckcc_section").show();
// });
// $("#nav-ckach-tab").click(function () {
//   $("#pay_with").val("ckach");
//   $("#payment_method").val("cardknox");
//   $("#xName").attr("required", false);
//   $("#authnet_card_num").attr("required", false);
//   $("#authnet_cvc").attr("required", false);
//   $("#authnet_exp").attr("required", false);
//   $("#authnet_zip").attr("required", false);
//   $("#expiryCC").attr("required", false);
//   $("#xrouting").attr("required", true);
//   $("#xAchName").attr("required", true);
//   $("#ojc_card_num").attr("required", false);
//   $("#ojc_expiry").attr("required", false);
//   $("#mtb_card_num").attr("required", false);
//   $("#mtb_expiry").attr("required", false);
//   $("#dfd_card_num").attr("required", false);
//   $("#dfd_cvc").attr("required", false);
//   $("#plg_card_num").attr("required", false);
//   $("#plg_expiry").attr("required", false);
//   $("#plg_cvv").attr("required", false);
//   $(".payment-tab").hide();
//   $(".ckach_section").show();
// });
// $("#nav-ojc-tab").click(function () {
//   $("#pay_with").val("ojc");
//   $("#payment_method").val("ojc_fund");
//   $("#xName").attr("required", false);
//   $("#expiryCC").attr("required", false);
//   $("#authnet_card_num").attr("required", false);
//   $("#authnet_cvc").attr("required", false);
//   $("#authnet_exp").attr("required", false);
//   $("#authnet_zip").attr("required", false);
//   $("#xrouting").attr("required", false);
//   $("#xAchName").attr("required", false);
//   $("#ojc_card_num").attr("required", true);
//   $("#ojc_expiry").attr("required", true);
//   $("#mtb_card_num").attr("required", false);
//   $("#mtb_expiry").attr("required", false);
//   $("#dfd_card_num").attr("required", false);
//   $("#dfd_cvc").attr("required", false);
//   $("#plg_card_num").attr("required", false);
//   $("#plg_expiry").attr("required", false);
//   $("#plg_cvv").attr("required", false);
//   $(".payment-tab").hide();
//   $(".ojc_section").show();
// });
// $("#nav-pp-tab").click(function () {
//   $("#pay_with").val("pp");
//   $("#payment_method").val("paypal");
//   $("#xName").attr("required", false);
//   $("#authnet_card_num").attr("required", false);
//   $("#authnet_cvc").attr("required", false);
//   $("#authnet_exp").attr("required", false);
//   $("#authnet_zip").attr("required", false);
//   $("#expiryCC").attr("required", false);
//   $("#xrouting").attr("required", false);
//   $("#xAchName").attr("required", false);
//   $("#ojc_card_num").attr("required", false);
//   $("#ojc_expiry").attr("required", false);
//   $("#mtb_card_num").attr("required", false);
//   $("#mtb_expiry").attr("required", false);
//   $("#dfd_card_num").attr("required", false);
//   $("#dfd_cvc").attr("required", false);
//   $("#plg_card_num").attr("required", false);
//   $("#plg_expiry").attr("required", false);
//   $("#plg_cvv").attr("required", false);
//   $(".payment-tab").hide();
//   $(".pp_section").show();
// });
// $("#nav-uepcc-tab").click(function () {
//   $("#pay_with").val("uepcc");
//   $("#payment_method").val("usaepay");
//   $("#xName").attr("required", false);
//   $("#authnet_card_num").attr("required", false);
//   $("#authnet_cvc").attr("required", false);
//   $("#authnet_exp").attr("required", false);
//   $("#authnet_zip").attr("required", false);
//   $("#expiryCC").attr("required", false);
//   $("#xrouting").attr("required", false);
//   $("#xAchName").attr("required", false);
//   $("#ojc_card_num").attr("required", false);
//   $("#ojc_expiry").attr("required", false);
//   $("#mtb_card_num").attr("required", false);
//   $("#mtb_expiry").attr("required", false);
//   $("#dfd_card_num").attr("required", false);
//   $("#dfd_cvc").attr("required", false);
//   $("#plg_card_num").attr("required", false);
//   $("#plg_expiry").attr("required", false);
//   $("#plg_cvv").attr("required", false);
//   $(".payment-tab").hide();
//   $(".uepcc_section").show();
// });
// $("#nav-stripe-tab").click(function () {
//   $("#pay_with").val("stripe");
//   $("#payment_method").val("stripe");
//   $("#xName").attr("required", false);
//   $("#authnet_card_num").attr("required", false);
//   $("#authnet_cvc").attr("required", false);
//   $("#authnet_exp").attr("required", false);
//   $("#authnet_zip").attr("required", false);
//   $("#expiryCC").attr("required", false);
//   $("#xrouting").attr("required", false);
//   $("#xAchName").attr("required", false);
//   $("#ojc_card_num").attr("required", false);
//   $("#ojc_expiry").attr("required", false);
//   $("#mtb_card_num").attr("required", false);
//   $("#mtb_expiry").attr("required", false);
//   $("#dfd_card_num").attr("required", false);
//   $("#dfd_cvc").attr("required", false);
//   $("#plg_card_num").attr("required", false);
//   $("#plg_expiry").attr("required", false);
//   $("#plg_cvv").attr("required", false);
//   $(".payment-tab").hide();
//   $(".stripe_section").show();
// });
// $("#nav-matbia-tab").click(function () {
//   $("#pay_with").val("matbia");
//   $("#payment_method").val("matbia");
//   $("#xName").attr("required", false);
//   $("#authnet_card_num").attr("required", false);
//   $("#authnet_cvc").attr("required", false);
//   $("#authnet_exp").attr("required", false);
//   $("#authnet_zip").attr("required", false);
//   $("#expiryCC").attr("required", false);
//   $("#xrouting").attr("required", false);
//   $("#xAchName").attr("required", false);
//   $("#ojc_card_num").attr("required", false);
//   $("#ojc_expiry").attr("required", false);
//   $("#mtb_card_num").attr("required", true);
//   $("#mtb_expiry").attr("required", true);
//   $("#dfd_card_num").attr("required", false);
//   $("#dfd_cvc").attr("required", false);
//   $("#plg_card_num").attr("required", false);
//   $("#plg_expiry").attr("required", false);
//   $("#plg_cvv").attr("required", false);
//   $(".payment-tab").hide();
//   $(".matbia_section").show();
// });
// $("#nav-donfund-tab").click(function () {
//   $("#pay_with").val("donfund");
//   $("#payment_method").val("donor_fund");
//   $("#xName").attr("required", false);
//   $("#authnet_card_num").attr("required", false);
//   $("#authnet_cvc").attr("required", false);
//   $("#authnet_exp").attr("required", false);
//   $("#authnet_zip").attr("required", false);
//   $("#expiryCC").attr("required", false);
//   $("#xrouting").attr("required", false);
//   $("#xAchName").attr("required", false);
//   $("#ojc_card_num").attr("required", false);
//   $("#ojc_expiry").attr("required", false);
//   $("#mtb_card_num").attr("required", false);
//   $("#mtb_expiry").attr("required", false);
//   $("#dfd_card_num").attr("required", true);
//   $("#dfd_cvc").attr("required", true);
//   $("#plg_card_num").attr("required", false);
//   $("#plg_expiry").attr("required", false);
//   $("#plg_cvv").attr("required", false);
//   $(".payment-tab").hide();
//   $(".donfund_section").show();
// });
// $("#nav-pledger-tab").click(function () {
//   $("#pay_with").val("pledger");
//   $("#xName").attr("required", false);
//   $("#authnet_card_num").attr("required", false);
//   $("#authnet_cvc").attr("required", false);
//   $("#authnet_exp").attr("required", false);
//   $("#authnet_zip").attr("required", false);
//   $("#expiryCC").attr("required", false);
//   $("#xrouting").attr("required", false);
//   $("#xAchName").attr("required", false);
//   $("#ojc_card_num").attr("required", false);
//   $("#ojc_expiry").attr("required", false);
//   $("#mtb_card_num").attr("required", false);
//   $("#mtb_expiry").attr("required", false);
//   $("#dfd_card_num").attr("required", false);
//   $("#dfd_cvc").attr("required", false);
//   $("#plg_card_num").attr("required", true);
//   $("#plg_expiry").attr("required", false);
//   $("#plg_cvv").attr("required", false);
//   $(".payment-tab").hide();
//   $(".pledger_section").show();
// });

// function submitRecPP(baseURL) {
//   var fname = $("#first_name").val();
//   var lname = $("#last_name").val();
//   var email = $("#email").val();
//   if (fname.trim() == "") {
//     $("#FormErr").text("First name is required field!");
//     $("#FormErr").fadeIn("slow");
//     setTimeout(function () {
//       $("#FormErr").fadeOut("slow");
//     }, 3000);
//     $("#first_name").focus();
//     return false;
//   } else if (lname.trim() == "") {
//     $("#FormErr").text("Last name is required field!");
//     $("#FormErr").fadeIn("slow");
//     setTimeout(function () {
//       $("#FormErr").fadeOut("slow");
//     }, 3000);
//     $("#last_name").focus();
//     return false;
//   } else if (email.trim() == "") {
//     $("#FormErr").text("Email is required field!");
//     $("#FormErr").fadeIn("slow");
//     setTimeout(function () {
//       $("#FormErr").fadeOut("slow");
//     }, 3000);
//     $("#email").focus();
//     return false;
//   } else {
//     $("#overlayloader").css("display", "block");
//     var formDataAll = $("#donate_form, #donate_form2").serialize();
//     $.post(
//       baseURL + "/actions/donation-actions.php",
//       {
//         userAction: "storeRecDataPP",
//         formdata: formDataAll,
//       },
//       function (data) {
//         console.log(data);
//         $("#recPayPalForm").submit();
//       }
//     );
//   }
// }

// function breakupDonation() {
//   $(".rec_btns").hide();
//   $("#don_recurring").removeAttr("checked");
//   $("#daily_rec").prop("checked", true);
//   $("#recurring_cycle").prop("selectedIndex", 0);
// }

// function getChangePrice(usdAmount) {
//   $("#donate_amount").attr("readonly", true);
//   $("#donate_amount").css({
//     cursor: "default",
//     border: "1px solid transparent",
//   });
//   $("#donate_currency").prop("selectedIndex", 0);
//   $("#cause_usd_price").val(usdAmount);
//   $("#donate_amount").val(usdAmount);
//   $(".currency-code").html("$");
//   $(".currency-symbol").html("USD");
//   var proFeeAmt = $("#fee_percentage").val();
//   var processFee = (usdAmount * proFeeAmt) / 100;
//   var currency = $("#donate_currency option:selected").text();
//   $("#process_fee").html(
//     "Add " +
//       currency +
//       processFee.toFixed(2) +
//       " to your payment to cover the " +
//       proFeeAmt +
//       "% credit card processing fee"
//   );
//   $("#don_process_fee").val(processFee.toFixed(2));

//   if ($("#isRec").val() == "1") {
//     var recInterval = $("#recurring_cycle option:selected").val();
//     var recMonthAmt = usdAmount / recInterval;
//     $("#rec_amount_full").html(currency + usdAmount);
//     $("#rec_amount_month").html(currency + recMonthAmt.toFixed(2));
//   }
//   cleanAppliedCoupon();
// }

// function manualAmount() {
//   $("#manual_amount").prop("checked", true);
//   $("#donate_amount").attr("readonly", false);
//   $("#donate_amount").css({ cursor: "text", border: "1px solid #212121" });
//   $("#donate_amount").focus();
//   //var tmpStr = $("#donate_amount").val();
//   $("#donate_amount").val("");
//   //$("#donate_amount").val(tmpStr);

//   $(".currency-code").html("$");
//   $(".currency-symbol").html("USD");
//   $("#donate_currency option[value=USD]").attr("selected", "selected");

//   var currency = $("#donate_currency option:selected").text();
//   $("#process_fee").html(
//     "Add " +
//       currency +
//       "0.00 to your payment to cover the credit card processing fee"
//   );

//   var isRecPay = $("#don_recurring").is(":checked") ? 1 : 0;
//   $("#isRec").val(isRecPay);

//   $("#rec_amount_full").html(currency + "0.00");

//   showPaymentSection();
// }

// function changeIntervals(recInterval) {
//   var currency = $("#donate_currency option:selected").text();
//   var amount = $("#donate_amount").val();
//   var recMonthAmt = amount / recInterval;
//   $("#rec_amount_full").html(currency + amount);
//   $("#rec_amount_month").html(currency + recMonthAmt.toFixed(2));
//   showPaymentSection();
// }

// function applyCoupon() {
//   var currency = $("#donate_currency option:selected").text();
//   var currencyCode = $("#donate_currency option:selected").val();
//   var baseURL = $("#baseURL").val();

//   if ($("#coupon").val() != "") {
//     $("#overlayloader").css("display", "block");
//     $.post(
//       baseURL + "/actions/app-actions.php",
//       {
//         userAction: "getCouponDetail",
//         couponCode: $("#coupon").val(),
//       },
//       function (data) {
//         if (data == "InvalidCode") {
//           $("#overlayloader").css("display", "none");
//           $("#cpMsg").text("Coupon code is invalid!");
//           $("#cpMsg").fadeIn("slow");
//           setTimeout(function () {
//             $("#cpMsg").fadeOut("slow");
//           }, 5000);
//         } else {
//           var jsondata = $.parseJSON(data);

//           var usdDiscount = jsondata.cp_discount;
//           if (currencyCode != "USD" || currencyCode != "CAD") {
//             $.post(
//               baseURL + "/actions/donation-actions.php",
//               {
//                 userAction: "getAmountRate",
//                 usdAmount: usdDiscount,
//                 toCurrency: currencyCode,
//               },
//               function (data) {
//                 var discAmount =
//                   jsondata.cp_discount_type == "amount"
//                     ? data
//                     : jsondata.cp_discount;
//                 $("#discountAmount").val(discAmount);
//                 $("#discountType").val(jsondata.cp_discount_type);
//                 var discType =
//                   jsondata.cp_discount_type == "amount"
//                     ? currency + data
//                     : jsondata.cp_discount + "%";
//                 $("#couponFee").html("-" + discType);

//                 $("#overlayloader").css("display", "none");

//                 $("#cpMsg").text(
//                   "Your (" +
//                     jsondata.cp_title +
//                     ") coupon is applied. (Discount: " +
//                     discType +
//                     " - Win Chances: " +
//                     jsondata.cp_chances +
//                     ")"
//                 );
//                 $("#cpMsg").fadeIn("slow");

//                 $("#couponID").val(jsondata.cp_id);
//                 $(".coupon_text").html(
//                   "Coupon: <small>" +
//                     jsondata.cp_title +
//                     " (" +
//                     jsondata.cp_code +
//                     ")<small>"
//                 );
//                 $(".coupon_amount").show();
//                 $("#applyCP").hide();
//                 $("#cleanCP").show();
//                 showPaymentSection();
//               }
//             );
//           } else {
//             $("#discountAmount").val(jsondata.cp_discount);
//             $("#discountType").val(jsondata.cp_discount_type);
//             var discType =
//               jsondata.cp_discount_type == "amount"
//                 ? currency + jsondata.cp_discount
//                 : jsondata.cp_discount + "%";
//             $("#couponFee").html("-" + discType);

//             $("#overlayloader").css("display", "none");

//             $("#cpMsg").text(
//               "Your (" +
//                 jsondata.cp_title +
//                 ") coupon is applied. (Discount: " +
//                 discType +
//                 " - Win Chances: " +
//                 jsondata.cp_chances +
//                 ")"
//             );
//             $("#cpMsg").fadeIn("slow");

//             $("#couponID").val(jsondata.cp_id);
//             $(".coupon_text").html(
//               "Coupon: <small>" +
//                 jsondata.cp_title +
//                 " (" +
//                 jsondata.cp_code +
//                 ")<small>"
//             );
//             $(".coupon_amount").show();
//             $("#applyCP").hide();
//             $("#cleanCP").show();
//             showPaymentSection();
//           }
//         }
//       }
//     );
//   }
// }

// function cleanAppliedCoupon() {
//   $("#discountAmount").val("");
//   $("#cpMsg").hide();
//   $("#cpMsg").text("");
//   $("#coupon").val("");
//   $("#couponID").val("0");
//   $(".coupon_text").html("Coupon:");
//   $("#couponFee").html("$0.00");
//   $(".coupon_amount").hide();
//   $("#applyCP").show();
//   $("#cleanCP").hide();
//   showPaymentSection();
// }

// $("#editPaymentMethod").click(function () {
//   $(".cc_section").hide();
//   $(".showPaymentMethod").show();
// });

// function chooseTicket(cpID, cpAmount, cpName, cpIsRec) {
//   $("#manual_amount").prop("checked", false);
//   $(".manual-amount-input").hide();
//   $("#custom_amount").val("");

//   $(".cause_ticket").removeClass("active");
//   $("#cp_" + cpID).addClass("active");

//   $("#pricing_options").val(cpName);
//   $("#donate_amount").val(cpAmount);

//   var isRecPay = $("#don_recurring").is(":checked") ? 1 : cpIsRec;
//   $("#isRec").val(isRecPay);

//   $("#donate_amount").attr("readonly", true);
//   $("#donate_amount").css({
//     cursor: "default",
//     border: "1px solid transparent",
//   });
//   $("#cause_usd_price").val(cpAmount);
//   $("#donate_amount").val(cpAmount);
//   $(".currency-code").html("$");
//   $(".currency-symbol").html("USD");

//   var proFeeAmt = $("#fee_percentage").val();
//   var processFee = (cpAmount * proFeeAmt) / 100;
//   var currency = "$";
//   $("#process_fee").html(
//     "Add " +
//       currency +
//       processFee.toFixed(2) +
//       " to your payment to cover the " +
//       proFeeAmt +
//       "% credit card processing fee"
//   );
//   $("#don_process_fee").val(processFee.toFixed(2));

//   $("#rec_amount_full").html(currency + cpAmount);

//   showPaymentSection();
// }

// function allowCustomTickets() {
//   if ($("#custom_tickets").is(":checked")) {
//     $(".cusTickets").show();
//   } else {
//     $(".cusTickets").hide();
//     $("#custom_tickets").removeAttr("checked");
//     $("#custom_tickets_count").val("1");
//   }
// }

// $("#recurring_interval").on("change", function () {
//   showPaymentSection();
// });

// let pageJustLoaded = true;

// window.onload = function () {
//   var CurrencyLetters = $("#currency-select option:selected").val();
//   var CurrencySymbol = $("#currency-select option:selected").text();
//   if ($("#multi_currency").val() == "1" && CurrencyLetters !== "USD") {
//     loadItemsCurrencyCode();
//   } else {
//     if (sessionStorage.getItem("first_name")) {
//       $("#collapseCart").collapse("show");
//     }
//   }
//   // localStorage.removeItem("giftSelections");
// };

// window.addEventListener("beforeunload", function () {
//   localStorage.setItem("isReloading", "true");
// });

// $(document).ready(function () {
//   $("#ticketOptionsSection").on("click", function () {
//     sessionStorage.setItem("selectedOptionId", "asdf");
//   });

//   $("#customAmount").on("change", function () {
//     sessionStorage.removeItem("original_amount_usd");
//   });
// });
// function formatCurrency(amount, currency) {
//   return Number(amount).toLocaleString("en", {
//     style: "currency",
//     currency: currency,
//     minimumFractionDigits: 0,
//     maximumFractionDigits: 0,
//   });
// }
// function loadItemsCurrencyCode() {
//   var currency = $("#currency-select option:selected").text();
//   var currencyCode = $("#currency-select option:selected").val();

//   sessionStorage.setItem("currency", currencyCode);

//   var usdAmount = 1;
//   var baseURL = $("#baseURL").val();
//   sessionCurrency = sessionStorage.getItem("sessionCurrency");
//   let causeId = $("#causeID").val();
//   if (
//     currencyCode !== "USD" &&
//     (typeof shouldProcessCurrency === "function"
//       ? shouldProcessCurrency(currencyCode, causeId)
//       : true)
//   ) {
//     $.post(
//       baseURL + "/actions/donation-actions.php",
//       {
//         userAction: "getAmountRate",
//         usdAmount: usdAmount,
//         toCurrency: currencyCode,
//       },
//       function (data) {
//         $("#overlayloader").css("display", "none");
//         if (currencyCode == "CAD") {
//           $(".input-wrapper .form-input").css("padding", "12px 18px 12px 94px");
//         } else {
//           $(".input-wrapper .form-input").css("padding", "12px 18px 12px 60px");
//         }

//         let htmlBlocks = document.getElementsByClassName("option-card");
//         let htmlBlocksArray = [...htmlBlocks];

//         htmlBlocksArray.forEach((htmlBlock) => {
//           let tempDiv = htmlBlock;

//           let amountElement = tempDiv.querySelector(".option-card__amount");
//           let otherCurrency = tempDiv.querySelector(".currency__amount");
//           let otherCurrencyMobile = tempDiv.querySelector(
//             ".currency__amount__mobile"
//           );

//           let amountHidden = tempDiv.querySelector(".ticket_amount_usd");
//           let amountText = amountHidden.value;
//           var convertedAmount = (Number(amountText) * data).toLocaleString(
//             "en-US",
//             {
//               style: "currency",
//               currency: currencyCode,
//               minimumFractionDigits: 0,
//               maximumFractionDigits: 0,
//             }
//           );
//           if (currencyCode == "CAD") {
//             amountElement.innerHTML = formatCurrency(
//               Number(amountText) * data,
//               currencyCode
//             );
//           } else {
//             amountElement.innerHTML = convertedAmount;
//           }

//           let otherCurrencyAmount = formatCurrency(amountHidden.value, "USD");
//           otherCurrency.innerHTML =
//             "<span style='font-weight:500;'>USD </span>" + otherCurrencyAmount;
//           otherCurrencyMobile.innerHTML =
//             "<span style='font-weight:500;'>USD </span>" + otherCurrencyAmount;
//         });
//         $(".currency-code").html(currency);
//         $("#donate_currency option[value=" + currencyCode + "]").attr(
//           "selected",
//           "selected"
//         );
//         $("#currency-select option[value='USD']").attr("selected", false);
//         $("#currency-select option[value=" + currencyCode + "]").attr(
//           "selected",
//           "selected"
//         );
//         // fromCheckout
//         if (sessionStorage.getItem("fromCheckout") == "yes") {
//           if (sessionStorage.getItem("first_name")) {
//             $("#collapseCart").collapse("show");
//           }
//         }
//       }
//     );
//   } else {
//     if (currencyCode == "CAD") {
//       $(".input-wrapper .form-input").css("padding", "12px 18px 12px 94px");
//     } else {
//       $(".input-wrapper .form-input").css("padding", "12px 18px 12px 60px");
//     }
//     $(".currency-code").html(currency);
//     $("#donate_currency option[value=" + currencyCode + "]").attr(
//       "selected",
//       "selected"
//     );
//     $("#currency-select option[value='USD']").attr("selected", false);
//     $("#currency-select option[value=" + currencyCode + "]").attr(
//       "selected",
//       "selected"
//     );
//     if (sessionStorage.getItem("first_name")) {
//       $("#collapseCart").collapse("show");
//     }
//   }
// }

// $("#currency-select").on("change", function () {
//   var currency = $("#currency-select option:selected").text();
//   var currencyCode = $("#currency-select option:selected").val();
//   var usdAmount = 1;
//   var baseURL = $("#baseURL").val();
//   updateSessionCurrency(baseURL, currencyCode, true);

//   sessionCurrency = sessionStorage.getItem("sessionCurrency");

//   location.reload();
// $("#overlayloader").css("display", "block");

// $.post(
//   baseURL + "/app-actions.php",
//   {
//     userAction: "getAmountRate",
//     usdAmount: usdAmount,
//     toCurrency: currencyCode,
//   },
//   function (data) {
//     $("#overlayloader").css("display", "none");
//     if (currencyCode == "CAD") {
//       $(".input-wrapper .form-input").css("padding", "12px 18px 12px 94px");
//     } else {
//       $(".input-wrapper .form-input").css("padding", "12px 18px 12px 60px");
//     }

//     let htmlBlocks = document.getElementsByClassName("option-card");
//     let htmlBlocksArray = [...htmlBlocks];

//     htmlBlocksArray.forEach((htmlBlock) => {
//       let tempDiv = htmlBlock;

//       let amountElement = tempDiv.querySelector(".option-card__amount");
//       let amountHidden = tempDiv.querySelector(".ticket_amount_usd");
//       let amountText = amountHidden.value;
//       var convertedAmount = (Number(amountText) * data).toLocaleString(
//         "en-US",
//         {
//           style: "currency",
//           currency: currencyCode,
//           minimumFractionDigits: 0,
//           maximumFractionDigits: 0,
//         }
//       );
//       amountElement.innerHTML = convertedAmount;
//     });

//     $.post(
//       baseURL + "/app-actions.php",
//       {
//         userAction: "getAmountRate",
//         usdAmount: 1,
//         toCurrency: currencyCode,
//       },
//       function (dataConvert) {
//         amountUsd = $("#usd_amount").val();

//         if (amountUsd !== null) {
//           var convertedAmount = (Number(amountUsd) * dataConvert).toFixed(0);
//           $("#donate_amount").val(convertedAmount);
//           $("#customAmount").val(convertedAmount);
//           $(".checkout-btn__amount").html(currencyCode + convertedAmount);
//           // $("#customAmount").trigger("input");
//         }
//       }
//     );
//     $(".ticket-options__control-title").html(
//       "Enter <mark>custom amount</mark> to see entry details"
//     );

//     $(".currency-code").html(currency);
//     $("#donate_currency option[value=" + currencyCode + "]").attr(
//       "selected",
//       "selected"
//     );
//     $("#currency-select option[value='USD']").attr("selected", false);
//     $("#currency-select option[value=" + currencyCode + "]").attr(
//       "selected",
//       "selected"
//     );

//     //$("#donate_currency").attr("disabled", "disabled");
//     setTimeout(function () {
//       showPaymentSection();
//     }, 300);
//   }
// );
// });

// let allDonorsLoaded = false;
// let isInitialLoadDonors = true;

// function loadDonors(isSortingChange = false, searchText = null) {
//   var baseURL = $("#baseURL").val();
//   let currentSortingOption = $(".team__select").find("option:selected").val();
//   let currentCount = isSortingChange ? 0 : $(".donor-card").length;
//   const recordsPerPage = 12;

//   // $(".search-text").va();

//   $.post(
//     baseURL + "/actions/donation-actions.php",
//     {
//       userAction: "loadMoreDonors",
//       causeID: $("#causeID").val(),
//       recordStartLimit: currentCount,
//       recordEndLimit: recordsPerPage,
//       currentSortingOption: currentSortingOption,
//       searchText: searchText,
//     },
//     function (data) {
//       $("#overlayloader").css("display", "none");
//       if (data.trim() == "") {
//         $(".load-more-donors")
//           .html("No more data")
//           .attr("disabled", true)
//           .removeAttr("style");
//         allDonorsLoaded = true;
//         if (searchText.trim() !== "") {
//           $(".donors-grid").html(data);
//         }
//         return;
//       } else {
//         var $data = $(data);
//         var dataCount = $data.filter(".donor-card").length;

//         if (isSortingChange) {
//           $(".donors-grid").html(data);
//         } else {
//           $(".donors-grid").append(data);
//         }
//         updateTimeElements();

//         if (dataCount < recordsPerPage) {
//           $(".load-more-donors")
//             .html("No more data")
//             .attr("disabled", true)
//             .removeAttr("style");
//           allDonorsLoaded = true;
//         } else {
//           $(".load-more-donors").html("Load More").attr("disabled", false);
//         }
//         if (isInitialLoadDonors) {
//           $(".donors-grid").animate(
//             {
//               scrollTop: $(".donors-grid").get(0).scrollHeight,
//             },
//             1000
//           );
//           isInitialLoadDonors = false;
//         }
//       }
//     }
//   );
// }

// function attachScrollListenerDonors() {
//   $(".donors-grid").on("scroll", function () {
//     clearTimeout(donortDebounceTimer);
//     donortDebounceTimer = setTimeout(() => {
//       if (allDonorsLoaded) return;
//       let div = $(this);
//       if (div.scrollTop() + div.innerHeight() >= div[0].scrollHeight - 100) {
//         let searchText = $(".search-text").val();
//         loadDonors(false, searchText);
//       }
//     }, 200);
//   });
// }

// let allTeamDonationsLoaded = false;
// let isInitialLoadTeamDonations = true;
// function loadTeamDonations(isSortingChange = false, searchText = null) {
//   var baseURL = $("#baseURL").val();
//   let currentSortingOption = $(".team__select").find("option:selected").val();
//   let currentCount = isSortingChange ? 0 : $(".team-card-donor").length;
//   const recordsPerPage = 12;

//   let teamName = $("#teamName").val();

//   $.post(
//     baseURL + "/actions/donation-actions.php",
//     {
//       userAction: "loadMoreTeamDonations",
//       teamId: $("#teamSlugId").val(),
//       recordStartLimit: currentCount,
//       recordEndLimit: recordsPerPage,
//       currentSortingOption: currentSortingOption,
//       searchText: searchText,
//     },
//     function (data) {
//       $("#overlayloader").css("display", "none");
//       if (data.trim() == "") {
//         $(".load-more-team-donors")
//           .html("No more data")
//           .attr("disabled", true)
//           .removeAttr("style");
//         allTeamDonationsLoaded = true;
//         if (searchText.trim() !== "") {
//           $(".team-donors").html(data);
//         }
//         return;
//       } else {
//         var $data = $(data);
//         var dataCount = $data.filter(".team-card-donor").length;
//         if (dataCount < recordsPerPage) {
//           $(".load-more-team-donors")
//             .html("No more data")
//             .attr("disabled", true)
//             .removeAttr("style");
//           allTeamDonationsLoaded = true;
//         } else {
//           $(".load-more-team-donors").html("Load More").attr("disabled", false);
//         }
//         if (isSortingChange) {
//           $(".team-donors").html(data);
//         } else {
//           $(".team-donors").append(data);
//         }
//         updateTimeElements();
//         if (isInitialLoadTeamDonations) {
//           $(".team-donors").animate(
//             {
//               scrollTop: $(".team-donors").get(0).scrollHeight,
//             },
//             1000
//           );
//           isInitialLoadTeamDonations = false;
//         }
//       }
//     }
//   );
// }

// let alloadTeamsLoaded = false;
// let isInitialLoadloadTeams = true;
// function loadTeams(isSortingChange = false, searchText = null) {
//   var baseURL = $("#baseURL").val();
//   let currentSortingOption = $(".team__select").find("option:selected").val();
//   let currentCount = isSortingChange ? 0 : $(".team-member-card").length;
//   const recordsPerPage = 6;
//   let teamName = $("#teamName").val();
//   $.post(
//     baseURL + "/actions/donation-actions.php",
//     {
//       userAction: "loadMoreTeams",
//       causeID: $("#causeID").val(),
//       recordStartLimit: currentCount,
//       recordEndLimit: recordsPerPage,
//       currentSortingOption: currentSortingOption,
//       searchText: searchText,
//     },
//     function (data) {
//       $("#overlayloader").css("display", "none");
//       if (data.trim() == "") {
//         $(".load-more-teams").html("No more data");
//         $(".load-more-teams").attr("disabled", true).removeAttr("style");
//         alloadTeamsLoaded = true;
//         if (searchText.trim() !== "") {
//           $(".team-members-grid").html(data);
//         }
//       } else {
//         var $data = $(data);
//         var dataCount = $data.filter(".team-member-card").length;
//         if (dataCount < recordsPerPage) {
//           $(".load-more-teams")
//             .html("No more data")
//             .attr("disabled", true)
//             .removeAttr("style");

//           alloadTeamsLoaded = true;
//         } else {
//           $(".load-more-teams").html("Load More").attr("disabled", false);
//         }
//         if (isSortingChange) {
//           $(".team-members-grid").html(data);
//         } else {
//           $(".team-members-grid").append(data);
//         }
//         if (teamName != "") {
//           $(".select-team").hide();
//         }
//         if (isInitialLoadloadTeams) {
//           $(".team-wrapper").animate(
//             {
//               scrollTop: $(".team-wrapper").get(0).scrollHeight,
//             },
//             1000
//           );
//           isInitialLoadloadTeams = false;
//         }
//         // let totalCards = $(".team-member-card").length;
//       }
//     }
//   );
// }

// $(document).ready(function () {
//   let debounceTimer;
//   $(document).on("keyup", ".search-text", function () {
//     clearTimeout(debounceTimer);
//     debounceTimer = setTimeout(() => {
//       let sorting = $(".team__select").val();
//       let searchItem = $(".search-text").attr("data-id");
//       let searchText = $(".search-text").val();
//       if (searchItem == "donor") {
//         loadDonors(sorting, searchText);
//       } else if (searchItem == "team") {
//         loadTeams(sorting, searchText);
//       } else {
//         loadTeamDonations(sorting, searchText);
//       }
//     }, 500);
//   });
// });

// let isTeamLoadButtonClicked = false;
// let teamLoadDebounceTimer;

// $(".load-more-teams").click(function () {
//   if (!isTeamLoadButtonClicked) {
//     isTeamLoadButtonClicked = true;
//     attachScrollListenerTeamsLoad();
//     $(this).hide();
//   }
//   let searchText = $(".search-text").val();
//   loadTeams(false, searchText);
//   setTimeout(() => {
//     selectTeamCard();
//   }, 1000);
//   $(".load-more-teams").html("Load More").removeAttr("disabled");
// });

// function attachScrollListenerTeamsLoad() {
//   $(".team-members-grid").on("scroll", function () {
//     clearTimeout(teamLoadDebounceTimer);
//     teamLoadDebounceTimer = setTimeout(() => {
//       if (alloadTeamsLoaded) return;
//       let div = $(this);
//       if (div.scrollTop() + div.innerHeight() >= div[0].scrollHeight - 100) {
//         let searchText = $(".search-text").val();
//         loadTeams(false, searchText);
//         setTimeout(() => {
//           selectTeamCard();
//         }, 1000);
//       }
//     }, 200);
//   });
// }

// let isDonorButtonClicked = false;
// let donortDebounceTimer;

// $(".load-more-donors").click(function () {
//   if (!isDonorButtonClicked) {
//     isDonorButtonClicked = true;
//     attachScrollListenerDonors();
//     $(this).hide();
//   }
//   let searchText = $(".search-text").val();
//   loadDonors(false, searchText);
// });

// let isTeamButtonClicked = false;
// let teamtDebounceTimer;

// $(".load-more-team-donors").click(function () {
//   if (!isTeamButtonClicked) {
//     isTeamButtonClicked = true;
//     attachScrollListenerTeams();
//     $(this).hide();
//   }
//   let searchText = $(".search-text").val();
//   loadTeamDonations(false, searchText);
// });

// function attachScrollListenerTeams() {
//   $(".team-donors").on("scroll", function () {
//     clearTimeout(teamtDebounceTimer);
//     teamtDebounceTimer = setTimeout(() => {
//       if (allTeamDonationsLoaded) return;
//       let div = $(this);
//       if (div.scrollTop() + div.innerHeight() >= div[0].scrollHeight - 100) {
//         let searchText = $(".search-text").val();
//         loadTeamDonations(false, searchText);
//       }
//     }, 200);
//   });
// }

// $(".team__select").on("change", function () {
//   allDonorsLoaded = false;
//   isInitialLoadDonors = true;
//   allTeamDonationsLoaded = false;
//   isInitialLoadTeamDonations = true;
//   alloadTeamsLoaded = false;
//   isInitialLoadloadTeams = true;
//   let sorting = $(this).val();
//   let searchText = $(".search-text").val();
//   if (searchText == "") {
//     $(".search-text").removeClass("expanded");
//   }
//   let searchItem = $(".search-text").attr("data-id");
//   if (searchItem == "donor") {
//     loadDonors(sorting, searchText);
//     $(".load-more-donors")
//       .html("Load More")
//       .attr("disabled", false)
//       .removeAttr("style");
//   } else if (searchItem == "team") {
//     loadTeams(sorting, searchText);
//     $(".load-more-teams")
//       .html("Load More")
//       .attr("disabled", false)
//       .removeAttr("style");
//   } else {
//     loadTeamDonations(sorting, searchText);
//     $(".load-more-team-donors")
//       .html("Load More")
//       .attr("disabled", false)
//       .removeAttr("style");
//   }
// });

// $(".search-text").on("input", function () {
//   var searchVal = $(this).val().toLowerCase();
//   if ($(this).val().trim() !== "") {
//     $(this).addClass("expanded");
//   }
// });

// $(".search-text").on("blur", function () {
//   if ($(this).val().trim() === "") {
//     $(this).removeClass("expanded");
//   }
// });

// $(document).on(
//   "click",
//   "#donors-btn, #members-btn,  #team-donors-btn",
//   function () {
//     $(".search-text").val("").trigger("input");

//     $(".team__select").val($(".team__select option:first").val());
//     $(".team__select").trigger("change");
//     if ($(this).attr("id") === "donors-btn") {
//       $(".search-text").attr("placeholder", "Search Donors...");
//       $(".search-text").attr("data-id", "donor");
//     } else if ($(this).attr("id") === "members-btn") {
//       $(".search-text").attr("placeholder", "Search Teams...");
//       $(".search-text").attr("data-id", "team");
//       $(".team__select ").val("largest");
//     } else {
//       $(".search-text").attr("placeholder", "Search...");
//       $(".search-text").attr("data-id", "teamDoners");
//     }
//   }
// );

// $(".search-text").keypress(function (e) {
//   if (e.which == 13) {
//     e.preventDefault();
//   }
// });

// $(document).on("click", ".select-team", function () {
//   let color = $("#themeColor").val();
//   let isActive = $(this)
//     .closest(".team-member-card")
//     .hasClass("active-team-member");

//   $(".team-member-card").removeClass("active-team-member");
//   $(".option-card-teams").removeClass("option-card-teams-select");
//   $(".select-team").css("background-color", color);
//   $(".select-team").html("Select Team");

//   if (!isActive) {
//     $(this).closest(".team-member-card").addClass("active-team-member");
//     $(this)
//       .closest(".team-member-card")
//       .find(".option-card-teams")
//       .addClass("option-card-teams-select");
//     $(this).css("background-color", "#333");
//     $(this).html("Clear Selection");
//     let teamid = $(this).closest(".team-member-card").data("id");
//     let teamName = $(this).closest(".team-member-card").data("name");

//     $("#teamId").val(teamid);
//     $(".cart__team").html("Team: " + teamName);
//   } else {
//     $("#teamId").val("");
//     $(".cart__team").html("");

//     $(this).css("background-color", color);
//     $(this).html("Select Team");
//   }
// });

// function selectTeamCard() {
//   let teamSlug = $("#teamSlug").val();
//   if (teamSlug) {
//     $(".team-member-card").each(function () {
//       let cardSlug = $(this).data("slug");
//       if (cardSlug === teamSlug) {
//         $(this).closest(".team-member-card").addClass("active-team-member");
//         $(this)
//           .closest(".team-member-card")
//           .find(".option-card-teams")
//           .addClass("option-card-teams-select");
//       } else {
//         $(this).removeClass("active-team-member");
//         $(this)
//           .closest(".team-member-card")
//           .find(".option-card-teams")
//           .removeClass("option-card-teams-select");
//       }
//     });
//   } else {
//     $(".team-member-card").removeClass("active-team-member");
//   }
// }

// $(document).ready(function () {
//   setTimeout(() => {
//     selectTeamCard();
//   }, 1000);
// });

// function timeAgo(jsonDate) {
//   const date = new Date(jsonDate);
//   const now = new Date();
//   const seconds = Math.floor((now - date) / 1000);
//   let interval;
//   let term = "";

//   if (seconds < 60) {
//     term = seconds + "s";
//   }

//   interval = Math.floor(seconds / 60);
//   if (!term && interval < 60) {
//     term = interval + "m";
//   }

//   interval = Math.floor(seconds / 3600);
//   if (!term && interval < 24) {
//     term = interval + "h";
//   }

//   interval = Math.floor(seconds / 86400);
//   if (!term && interval < 7) {
//     term = interval + "d";
//   }

//   if (term) {
//     return term + " ago";
//   }

//   const options = { month: "short", day: "numeric" };
//   if (date.getFullYear() !== now.getFullYear()) {
//     options.year = "numeric";
//   }

//   return date.toLocaleDateString("en-US", options);
// }

// function updateTimeElements() {
//   document.querySelectorAll("time[data-date]").forEach(function (timeElem) {
//     var originalDate = timeElem.getAttribute("data-date");
//     var formattedDate = timeAgo(originalDate);
//     timeElem.textContent = formattedDate;
//   });
// }

// // function validatePhone(event) {
// //   var inputField = event.target;
// //   var input = inputField.value;
// //   var regex =
// //     /^(?:\+1|1)?(?:\s|-|\.)?\(?(\d{3})\)?(?:\s|-|\.)?(\d{3})(?:\s|-|\.)?(\d{4})$/;

// //   if (!regex.test(input)) {
// //     inputField.classList.add("invalid");
// //   } else {
// //     inputField.classList.remove("invalid");
// //   }
// // }

// // function setupPhoneValidation() {
// //   const phoneInput = document.getElementById("phone");

// //   if (phoneInput) {
// //     phoneInput.addEventListener("input", validatePhone);
// //   }
// // }

// // // document.getElementById("phone").addEventListener("input", validatePhone);

// // setupPhoneValidation();

// $(document).on("change", "#donate-anonymously", function () {
//   if ($(this).is(":checked")) {
//     $("#anonymousDonation").val(1);
//   } else {
//     $("#anonymousDonation").val("");
//   }
// });

// document.addEventListener("DOMContentLoaded", function () {
//   const inputs = document.querySelectorAll("#donate_form .form-input");

//   inputs.forEach((input) => {
//     const savedValue = sessionStorage.getItem(input.name);
//     if (savedValue) {
//       input.value = savedValue;
//     }

//     input.addEventListener("input", function () {
//       sessionStorage.setItem(input.name, input.value);
//     });

//     input.addEventListener("change", function () {
//       sessionStorage.setItem(input.name, input.value);
//     });
//   });
// });

// $("#pac-input").on("change input ", function () {
//   setTimeout(() => {
//     sessionStorage.setItem("city", $("#city").val());
//     sessionStorage.setItem("state", $("#state").val());
//     sessionStorage.setItem("zipcode", $("#zipcode").val());
//     sessionStorage.setItem("country", $("#country").val());
//   }, 500);
// });

// $(".card_btn_general").on("click", function () {
//   let amount = $(this).data("amount");

//   $("#donate_amount").val(amount);
//   $("#usd_amount").val(amount);
//   $(".checkout-btn__amount, .checkout-btn .divider").css("display", "block");
//   $("#regularAmount").val(formatCurrency(amount));
//   $(".checkout-btn__amount").text(formatCurrency(amount));
// });
// function formatCurrency(amount, currency = "USD") {
//   amount = parseFloat(amount);
//   return amount.toLocaleString("en-US", {
//     style: "currency",
//     currency: "USD",
//     minimumFractionDigits: 0,
//     maximumFractionDigits: 0,
//   });
// }

// $(document).on("click", ".gift_tab", function () {
//   $(".gift_tab").removeClass("active");
//   $(this).addClass("active");
// });
// $(document).on("click", ".detail_btn", function () {
//   $("#giftDetailModal").modal("hide");
//   let details = $(this).data("details");
//   let giftImage = $(this).data("giftimg");
//   $("#giftDetailModal")
//     .find("#GiftDetails")
//     .html(
//       '<img src="' +
//         giftImage +
//         '" alt=""><br><p class="text-start p-2">' +
//         details +
//         "</p>"
//     );
//   if (details != null) {
//     var myModal = new bootstrap.Modal(
//       document.getElementById("giftDetailModal")
//     );
//     myModal.show();
//     $(".modal-backdrop").remove();
//   }
// });

// $(document).on("click", ".gift_card_body", function (e) {
//   if (!$(e.target).closest(".overlay_section").length) {
//   } else {
//     e.preventDefault();
//   }
// });

// $(document).on("dblclick", ".cart__heading", function clearAllFields() {
//   console.log("removing on dbl click");

//   const input = document.querySelectorAll("#donate_form .form-input");

//   input.forEach((input) => {
//     input.value = "";
//   });
//   // Select the div with the class 'all-fields'
//   var fieldsContainer = document.querySelector(".hiddenFields");

//   // Find all input elements within that div
//   var inputs = fieldsContainer.querySelectorAll("input");

//   // Loop through each input and set its value to an empty string
//   inputs.forEach(function (input) {
//     input.value = "";

//     // Uncheck checkboxes
//     if (input.type === "checkbox") {
//       input.checked = false;
//     }
//   });

//   sessionStorage.clear();
//   $("#customAmount").val(0).trigger("input");
//   // $(".prize-split__btn").trigger("click");
//   $(".prize-split__btn").removeClass("active");
//   $("#prize_split_amount").val("");
//   $("#prize_split_entries").val("");
//   localStorage.removeItem("usd_amount_Session");
//   location.reload();
// });

// $(document).ready(function () {
//   $("#custom_recurring_cycle").on("input", function () {
//     var value = $(this).val();
//     if (value > 12) {
//       $(this).val(12);
//     }
//   });
// });

// function SponsorMeal(
//   baseURL,
//   eventName,
//   eventPrice,
//   eventID,
//   locationID,
//   locationName,
//   groupID,
//   sponsorType,
//   quantity,
//   dayName = ""
// ) {
//   $(".dedicateDonationCheckbox").addClass("d-none");
//   if (groupID == 4) {
//     $(".dedicateDonationCheckbox").removeClass("d-none");
//   }
//   $.post(
//     baseURL + "/checkout-cart.php",
//     {
//       action: "addToCart",
//       eventID: eventID,
//       eventPrice: eventPrice,
//       eventName: eventName,
//       locationID: locationID,
//       locationName: locationName,
//       groupID: groupID,
//       sponsorType: sponsorType,
//       quantity: quantity,
//       dayName: dayName,
//     },
//     function (data) {
//       var location = locationName != "Any" ? " (" + locationName + ")" : "";
//       $("#EventCartModalTitle").html(eventName + location);
//       $("#cart_modal").html(data);
//       $("#EventCartModal").modal("show");
//     }
//   );
// }
// function numberFormat(number) {
//   if (isNaN(number)) return number;
//   return new Intl.NumberFormat().format(number);
// }
// function changePriceQTY(evtPrice, evtQTY, btnID) {
//   var portions = Number(evtQTY);
//   var eventTotalPrice = portions * Number(evtPrice);
//   $("#EventAmount").val(numberFormat(eventTotalPrice.toFixed(0)));
//   var portionsValue =
//     portions % 1 !== 0 ? portions.toFixed(2) : portions.toFixed(0);
//   $("#no_of_portions").val(portionsValue);

//   setPortionsTextPlural(portions);
//   $(".pricing-buttons").removeClass("active");
//   $("#prc_" + btnID).addClass("active");
// }

// function setPortionsTextPlural(numPortions) {
//   if (!numPortions) {
//     $(".portions-packages").hide();
//     return;
//   }
//   $(".portions-packages").show();

//   let v = $(".portions-packages").text();
//   if (numPortions > 1 && !v.endsWith("s")) v += "s";
//   else if (numPortions == 1 && v.endsWith("s")) v = v.slice(0, -1);
//   $(".portions-packages").text(v);
// }

// function GoToCheckout(homeURL, skipDedication = false) {
//   console.log("GoToCheckout", skipDedication);
//   if (
//     !skipDedication &&
//     document.getElementById("dedicateDonationCheckbox").checked
//   ) {
//     OpenDedicateMealModal();
//   } else {
//     AddToCart(homeURL, true);
//   }
// }
// function allowCustomPrice(btnID) {
//   $("#EventAmount").val("");
//   $("#no_of_portions").val("");
//   $("#EventAmount").focus();

//   setPortionsTextPlural(0);
//   $(".pricing-buttons").removeClass("active");
//   $("#prc_" + btnID).addClass("active");
// }
// function formatCurrency1(amount, currency = "USD") {
//   return amount.toLocaleString("en-US", {
//     style: "currency",
//     currency: currency,
//     minimumFractionDigits: 2,
//     maximumFractionDigits: 2,
//   });
// }
// function AddToCart(baseURL, redirectToCheckout = false) {
//   let eventAmount = $("#EventAmount").val();
//   if ($("#no_of_portions").val() < 1 && $("#EventAmount").val() < 1) {
//     $("#cartError").text("Amount or Quantity should not be empty!");
//     $("#cartError").fadeIn("slow");
//     setTimeout(function () {
//       $("#cartError").fadeOut("slow");
//     }, 4000);
//   } else {
//     $.post(
//       baseURL + "/checkout-cart.php",
//       {
//         action: "fillCart",
//         eventID: $("#EventID").val(),
//         eventPrice: $("#EventPrice").val(),
//         eventName: $("#EventName").val(),
//         eventAmount: $("#EventAmount").val(),
//         locationID: $("#eventLocation").val(),
//         locationName: $("#eventLocationName").val(),
//         groupID: $("#GroupID").val(),
//         portions: $("#no_of_portions").val(),
//         donateForTheDay: $("#donateForTheDay").val(),
//         donatedToFirstName: $("#donatedToFirstName").val(),
//         donatedToLastName: $("#donatedToLastName").val(),
//         donatedToEmail: $("#donatedToEmail").val(),
//         donatedToMsg: $("#donatedToMsg").val(),
//       },
//       function (data) {
//         $.post(
//           baseURL + "/checkout-cart.php",
//           {
//             action: "ViewCart",
//           },
//           function (cartData) {
//             $("#mini-cart").html(cartData);
//             if (redirectToCheckout) {
//               // window.location.href = baseURL + "/checkout";

//               $("#regularAmountReuglar").val(eventAmount);
//               $("#donate_amount").val(eventAmount);
//               $("#usd_amount").val(eventAmount);
//               $(".cart__title.js-cart-summary-amount").html(
//                 "Your Donation: " + formatCurrency1(eventAmount)
//               );
//               $(".checkout-btn__amount  ").html(formatCurrency1(eventAmount));

//               $(".cart--regular").removeClass("dnone");
//               showPaymentSection();
//             }
//           }
//         );
//         $("#EventCartModal").modal("hide");
//       }
//     );
//   }
// }