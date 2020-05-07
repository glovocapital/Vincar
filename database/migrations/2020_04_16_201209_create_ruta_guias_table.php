<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRutaGuiasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ruta_guias', function (Blueprint $table) {
            $table->bigIncrements('ruta_guias_id');
            
            $table->unsignedBigInteger('ruta_id');
            $table->foreign('ruta_id')->references('ruta_id')->on('rutas')->onUpdate('cascade')->onDelete('cascade');

            $table->unsignedBigInteger('guia_id');
            $table->foreign('guia_id')->references('guia_id')->on('guias')->onUpdate('cascade')->onDelete('cascade');

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
        Schema::dropIfExists('ruta_guias');
    }
}
