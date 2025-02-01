<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOutletsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('outlets', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->string('email')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('phone', 100)->nullable();
            $table->timestamp('phone_verified_at')->nullable();
            $table->string('website', 100)->nullable();
            $table->integer('rank')->unsigned()->nullable();
            $table->string('email_verify_token')->nullable();
            $table->string('phone_verify_code')->nullable();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('outlets');
    }
}
