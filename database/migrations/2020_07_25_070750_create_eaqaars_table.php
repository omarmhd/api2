<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEaqaarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eaqaars', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('type_id')->nullable();
            $table->unsignedBigInteger('plan_id')->nullable();
            $table->foreign('type_id')->references('id')->on('types')->onDelete('cascade');
            $table->foreign('plan_id')->references('id')->on('plans')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

             $table->string('state');

            $table->string('area');
            $table->string('square');
            $table->string('Part_number');
            $table->string('space');
            $table->string('Survey_number');
            $table->string('name_seller');
            $table->string('card_seller');
            $table->string('phone_seller');
            $table->date('date_buy');
            $table->string('price_buy');
            $table->string('image')->nullable();
            $table->string('image_card')->nullable();

            $table->string('Remaining_amount');
            $table->string('Downpayment');
            $table->string('estimated_price');
            $table->string('url')->nullable();

            $table->string('detials')->nullable();
            $table->string('use')->nullable();

            $table->string('status')->default('متوفر');
            $table->date('due_date');

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('eaqaars');
    }
}
