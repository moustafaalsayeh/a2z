<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->morphs('addressable');
            $table->unsignedBigInteger('country_id')->nullable();
            $table->unsignedBigInteger('city_id')->nullable();
            $table->string('title', 255)->nullable();
            $table->string('address_details', 255)->nullable();
            $table->string('state', 50)->nullable();
            $table->string('postal_code', 50)->nullable();
            $table->string('street', 100)->nullable();
            $table->string('building', 100)->nullable();
            $table->string('landmark', 100)->nullable();
            $table->string('apartment', 50)->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('mobile', 50)->nullable();
            $table->string('flat', 50)->nullable();
            $table->double('lat', 11, 8)->nullable();
            $table->double('lng', 11, 8)->nullable();
            $table->boolean('is_primary')->nullable();
            $table->boolean('is_shipping')->nullable();

            $table->timestamps();

            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('addresses');
    }
}
