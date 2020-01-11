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
            $table->date('detalle_tour_fec_inicio')->nullable();
            $table->date('detalle_tour_fec_fin')->nullable();
            $table->boolean('detalle_tour_entregado');
            $table->text('detalle_tour_observaciones');

            $table->unsignedBigInteger('salida_destino_id');
            $table->foreign('salida_destino_id')->references('destino_id')->on('destinos')->onUpdate('cascade')->onDelete('cascade');

            $table->unsignedBigInteger('llegada_destino_id');
            $table->foreign('llegada_destino_id')->references('destino_id')->on('destinos')->onUpdate('cascade')->onDelete('cascade');

            $table->unsignedBigInteger('tour_id');
            $table->foreign('tour_id')->references('tour_id')->on('tours')->onUpdate('cascade')->onDelete('cascade');

            $table->unsignedBigInteger('vin_id');
            $table->foreign('vin_id')->references('vin_id')->on('vins')->onUpdate('cascade')->onDelete('cascade');

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
