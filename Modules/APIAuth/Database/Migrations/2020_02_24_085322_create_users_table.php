<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->unsignedBigInteger('currency_id')->nullable();
            $table->unsignedBigInteger('language_id')->nullable();
            $table->timestamps();
            $table->string('type', 100);
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('username')->unique()->nullable();
            $table->string('email')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone', 100)->unique()->nullable();
            $table->timestamp('phone_verified_at')->nullable();
            $table->integer('gender')->unsigned()->default(0);
            $table->date('birthdate')->nullable();
            $table->boolean('is_blocked')->default(false);
            $table->string('email_verify_token')->nullable();
            $table->string('phone_verify_code')->nullable();
            $table->rememberToken();
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
