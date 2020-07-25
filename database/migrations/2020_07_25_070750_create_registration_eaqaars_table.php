<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegistrationEaqaarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registration_eaqaars', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('type_id');
            $table->unsignedInteger('plan_id');

            $table->string('state');

            $table->string('area');
            $table->string('square');
            $table->string('Part_number');
            $table->string('space');
            $table->string('Survey_number');
            $table->string('name_seller');
            $table->string('card_seller');
            $table->string('phone_seller');
            $table->string('name_buyer');
            $table->string('card_buyer');
            $table->string('phone_seller');
            $table->string('date_buy');
            $table->string('price_buy');
            $table->string('Downpayment');
            $table->string('estimated_price');

            $table->timestamps();
            $table->foreign('type_id')->references('id')->on('types')->onDelete('cascade');
            $table->foreign('plan_id')->references('id')->on('plans')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('registration_eaqaars');
    }
}
