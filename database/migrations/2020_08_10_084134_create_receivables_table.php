<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceivablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receivables', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('eaqaar_id')->nullable();
            $table->unsignedBigInteger('sold_id')->nullable();

            $table->string('user_name');

            $table->string('type');
            $table->string('Remaining_amount');
            $table->date('date');

            $table->timestamps();
            $table->foreign('eaqaar_id')->references('id')->on('eaqaars')->onDelete('cascade');
            $table->foreign('sold_id')->references('id')->on('sold_eaqaars')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('receivables');
    }
}
