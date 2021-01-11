<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoricoToursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('historico_tours', function (Blueprint $table) {
            $table->bigIncrements('historico_tour_id');

            $table->unsignedBigInteger('tour_id');
            $table->foreign('tour_id')->references('tour_id')->on('tours')->onUpdate('cascade')->onDelete('cascade');

            $table->unsignedBigInteger('ruta_id')->nullable();
            $table->foreign('ruta_id')->references('ruta_id')->on('rutas')->onUpdate('cascade')->onDelete('cascade');

            $table->unsignedBigInteger('vin_id')->nullable();
            $table->foreign('vin_id')->references('vin_id')->on('vins')->onUpdate('cascade')->onDelete('cascade');

            $table->unsignedBigInteger('cliente_id')->nullable();
            $table->foreign('cliente_id')->references('empresa_id')->on('empresas')->onUpdate('cascade')->onDelete('cascade');

            $table->date('historico_tour_fecha_inicio');
            $table->date('historico_tour_fecha_fin')->nullable();

            $table->unsignedBigInteger('proveedor_id');
            $table->foreign('proveedor_id')->references('empresa_id')->on('empresas')->onUpdate('cascade')->onDelete('cascade');

            $table->string('historico_tour_condicion_entrega')->nullable(); // Extraer valor del resultado de la inspección por medio de la apk.
            $table->string('historico_tour_numero_guia_ruta')->nullable(); // Se obtiene de la relación ruta_guias.

            $table->text('historico_tour_descripcion');

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
        Schema::dropIfExists('historico_tours');
    }
}
