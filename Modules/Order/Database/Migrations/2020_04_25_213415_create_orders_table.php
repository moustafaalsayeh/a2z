<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('outlet_id')->nullable();
            $table->unsignedBigInteger('delivery_man_id')->nullable();

            $table->string('payment_method', 100);
            $table->string('prepration_time_minutes', 100)->nullable();
            $table->string('prepration_time_days', 100)->nullable();
            $table->integer('delivery_time')->unsigned()->default(0);
            $table->integer('delivery_fees')->unsigned()->default(0);
            $table->string('status', 100);
            $table->dateTime('accepted_at')->nullable();
            $table->dateTime('delivering_at')->nullable();
            $table->dateTime('completed_at')->nullable();

            $table->timestamps();

            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            // $table->foreign('delivery_man_id')->references('id')->on('users');
            // $table->foreign('outlet_id')->references('id')->on('outlets');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
