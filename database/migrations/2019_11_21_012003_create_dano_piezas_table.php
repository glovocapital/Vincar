<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDanoPiezasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dano_piezas', function (Blueprint $table) {
            $table->bigIncrements('dano_pieza_id');
            $table->text('dano_pieza_observaciones');
            $table->unsignedBigInteger('inspeccion_id');
            $table->foreign('inspeccion_id')->references('inspeccion_id')->on('inspecciones');
            $table->unsignedBigInteger('pieza_id');
            $table->foreign('pieza_id')->references('pieza_id')->on('piezas');
            $table->unsignedBigInteger('tipo_dano_id');
            $table->foreign('tipo_dano_id')->references('tipo_dano_id')->on('tipo_danos');
            $table->unsignedBigInteger('gravedad_id');
            $table->foreign('gravedad_id')->references('gravedad_id')->on('gravedades');
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
        Schema::dropIfExists('dano_piezas');
    }
}
