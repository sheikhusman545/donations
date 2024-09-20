<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaction_detail', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('donation_id');
            $table->bigInteger('card_number')->nullable();
            $table->string('card_type')->nullable();
            $table->text('card_token')->nullable();
            $table->bigInteger('card_expiry')->nullable();
            $table->string('pay_method')->nullable();
            $table->string('error_detail')->nullable();
            $table->bigInteger('customer_id')->nullable();
            $table->timestamps();
            $table->foreign('donation_id')->references('id')->on('donations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_detail');
    }
};
