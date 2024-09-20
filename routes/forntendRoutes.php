<?php

use App\Http\Controllers\donationController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\StripeController;
use Illuminate\Support\Facades\Route;


Route::get('/', [FrontendController::class, 'index'])->name('home');
Route::get('/general/thank-you', [FrontendController::class, 'thank_you'])->name('general.thank-you');


Route::get('/campaign/{campaign}', [FrontendController::class, 'raffle'])->name('raffle');


Route::post('/payment/{campaign}', [PaymentController::class, 'processPayment'])->name('payment.process');
Route::post('/campaign/create_team', [FrontendController::class, 'create_team'])->name('campaign.create_team');
