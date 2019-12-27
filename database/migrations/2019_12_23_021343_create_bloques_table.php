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
            $table->decimal('bloque_coord_lon', 10,8);
            $table->decimal('bloque_coord_lat', 10,8);
            
            $table->unsignedBigInteger('patio_id');
            $table->foreign('patio_id')->references('patio_id')->on('patios');
            
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
