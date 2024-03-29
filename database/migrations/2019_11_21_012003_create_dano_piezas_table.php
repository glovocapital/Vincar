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
            $table->foreign('inspeccion_id')->references('inspeccion_id')->on('inspecciones')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('pieza_id');
            $table->foreign('pieza_id')->references('pieza_id')->on('piezas')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('tipo_dano_id');
            $table->foreign('tipo_dano_id')->references('tipo_dano_id')->on('tipo_danos')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('gravedad_id');
            $table->foreign('gravedad_id')->references('gravedad_id')->on('gravedades')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('pieza_sub_area_id')->nullable();
            $table->foreign('pieza_sub_area_id')->references('pieza_sub_area_id')->on('pieza_sub_areas')->onUpdate('cascade')->onDelete('cascade');
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
