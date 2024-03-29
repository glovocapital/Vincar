<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBloquesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bloques', function (Blueprint $table) {
            $table->bigIncrements('bloque_id');
            $table->string('bloque_nombre');
            $table->string('bloque_filas');
            $table->string('bloque_columnas');
            
            $table->unsignedBigInteger('patio_id');
            $table->foreign('patio_id')->references('patio_id')->on('patios')->onUpdate('cascade')->onDelete('cascade');;
            
            $table->softDeletes();
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
        Schema::dropIfExists('bloques');
    }
}
