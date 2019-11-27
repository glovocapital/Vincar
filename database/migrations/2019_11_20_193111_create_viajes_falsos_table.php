<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateViajesFalsosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('viajes_falsos', function (Blueprint $table) {
            $table->bigIncrements('viaje_falso_id');
            $table->date('viaje_falso_fecha');
            $table->bigIncrements('viaje_falso_id');

            $table->unsignedBigInteger('camion_id');
            $table->foreign('camion_id')->references('camion_id')->on('camiones');
            $table->unsignedBigInteger('conductor_id');
            $table->foreign('conductor_id')->references('user_id')->on('users');
            $table->unsignedBigInteger('empresa_id');
            $table->foreign('empresa_id')->references('empresa_id')->on('empresas');
            
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
        Schema::dropIfExists('viajes_falsos');
    }
}
