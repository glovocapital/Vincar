<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetalleToursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_tours', function (Blueprint $table) {
            $table->bigIncrements('detalle_tour_id');

            $table->string('detalle_tour_descripcion');

            $table->unsignedBigInteger('salida_destino_id');
            $table->foreign('salida_destino_id')->references('destino_id')->on('destinos');

            $table->unsignedBigInteger('llegada_destino_id');
            $table->foreign('llegada_destino_id')->references('destino_id')->on('destinos');

            $table->unsignedBigInteger('tour_id');
            $table->foreign('tour_id')->references('tour_id')->on('tours');

            $table->unsignedBigInteger('vin_id');
            $table->foreign('vin_id')->references('vin_id')->on('vins');

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
        Schema::dropIfExists('detalle_tours');
    }
}
