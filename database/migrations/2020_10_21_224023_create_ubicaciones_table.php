<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUbicacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ubicaciones', function (Blueprint $table) {
            $table->bigIncrements('ubicacion_id');
            $table->string('ubicacion_latitud');
            $table->string('ubicacion_longitud');

            $table->unsignedBigInteger('ruta_id');
            $table->foreign('ruta_id')->references('ruta_id')->on('rutas')->onUpdate('cascade')->onDelete('cascade');

            $table->dateTime('fecha_ubicacion_actual')->nullable();
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
        Schema::dropIfExists('ubicaciones');
    }
}
