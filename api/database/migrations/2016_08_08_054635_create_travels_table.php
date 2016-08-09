<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTravelsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('travels', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('driver_id');
            $table->decimal('latitude_origin', 10, 8);
            $table->decimal('longitude_origin', 11, 8);
            $table->unsignedInteger('client_id');
            $table->decimal('latitude_destination', 10, 8);
            $table->decimal('longitude_destination', 11, 8);
            $table->timestamps();

            $table->foreign('driver_id')->references('id')->on('drivers');
            $table->foreign('client_id')->references('id')->on('clients');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('travels');
    }
}
