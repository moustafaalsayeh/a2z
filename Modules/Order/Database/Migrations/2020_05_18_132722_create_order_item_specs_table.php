<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderItemSpecsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_item_specs', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->unsignedBigInteger('order_item_id');
            $table->unsignedBigInteger('prod_spec_id');
            $table->timestamps();

            $table->string('spec_title', 100)->nullable();
            $table->string('answer_string', 1000)->nullable();
            $table->integer('answer_price')->unsigned()->nullable()->default(0);
            $table->string('answer_ids', 1000)->nullable();

            $table->foreign('order_item_id')->references('id')->on('order_items')->onDelete('cascade');
            // $table->foreign('prod_spec_id')->references('id')->on('product_specifications');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_item_specs');
    }
}
