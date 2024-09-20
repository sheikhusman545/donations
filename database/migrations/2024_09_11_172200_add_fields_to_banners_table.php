<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->string('text_secondary_button')->nullable()->after('text_button'); // replace 'existing_column' with the actual column name after which you want to add the new columns
            $table->string('text_secondary_button_link')->nullable()->after('text_secondary_button');
            $table->string('prize_image')->nullable()->after('text_secondary_button_link');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->dropColumn('text_secondary_button');
            $table->dropColumn('text_secondary_button_link');
            $table->dropColumn('prize_image');
        });
    }
}
