<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSoldEaqaarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sold_eaqaars', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('eaqaar_id')->nullable();

            $table->string('name_buyer');

            $table->string('card_buyer');
            $table->string('phone_buyer');
            $table->double('price_sell');
            $table->date('Date_sale');
            $table->string('Remaining_amount');
            $table->string('Downpayment');
            $table->string('type')->nullable();
            $table->string('Partial_condition')->nullable();

            $table->date('due_date')->nullable();;
            $table->string('image_card')->nullable();
            $table->double('profit_company');
            $table->string('notes')->nullable();

            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('eaqaar_id')->references('id')->on('eaqaars')->onDelete('cascade');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sold_eaqaars');
    }
}
