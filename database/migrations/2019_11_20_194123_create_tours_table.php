<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateToursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tours', function (Blueprint $table) {
            $table->bigIncrements('tour_id');
            $table->string('tour_guia');
            $table->date('tour_fec_inicio');
            $table->date('tour_fec_fin');
            $table->boolean('tour_finalizado');

            $table->unsignedBigInteger('camion_id');
            $table->foreign('camion_id')->references('camion_id')->on('camiones')->onUpdate('cascade')->onDelete('cascade');

            $table->unsignedBigInteger('cliente_id');
            $table->foreign('cliente_id')->references('user_id')->on('users')->onUpdate('cascade')->onDelete('cascade');

            $table->unsignedBigInteger('proveedor_id');
            $table->foreign('proveedor_id')->references('empresa_id')->on('empresas')->onUpdate('cascade')->onDelete('cascade');

            $table->unsignedBigInteger('salida_destino_id');
            $table->foreign('salida_destino_id')->references('destino_id')->on('destinos')->onUpdate('cascade')->onDelete('cascade');

            $table->unsignedBigInteger('llegada_destino_id');
            $table->foreign('llegada_destino_id')->references('destino_id')->on('destinos')->onUpdate('cascade')->onDelete('cascade');

            $table->unsignedBigInteger('remolque_id');
            $table->foreign('remolque_id')->references('remolque_id')->on('remolques')->onUpdate('cascade')->onDelete('cascade');

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
        Schema::dropIfExists('tours');
    }
}
