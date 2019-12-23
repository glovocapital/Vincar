<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vins', function (Blueprint $table) {
            $table->bigIncrements('vin_id');
            $table->string('vin_codigo')->unique();
            $table->string('vin_patente')->nullable();
            $table->string('vin_modelo');
            $table->string('vin_marca');
            $table->string('vin_color');
            $table->string('vin_motor')->nullable();
            $table->string('vin_segmento')->nullable();
            $table->date('vin_fec_ingreso');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('user_id')->on('users');
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
        Schema::dropIfExists('vins');
    }
}
