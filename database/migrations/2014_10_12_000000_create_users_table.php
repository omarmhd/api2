<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('login_name');

            $table->string('remember_token', 100)->nullable();;
            $table->char('api_token',60)->nullable();
            $table->string('Role')->default('2')->nullable(true);
            $table->date('date_work');
            $table->string('address');
            $table->string('phone');
            $table->string('card');
            $table->string('Commission');
            $table->string('password');

            $table->string('image');

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
        Schema::dropIfExists('users');
    }
}
