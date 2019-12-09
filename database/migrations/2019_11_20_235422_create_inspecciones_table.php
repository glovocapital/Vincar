<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInspeccionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inspecciones', function (Blueprint $table) {
            $table->bigIncrements('inspeccion_id');
            $table->date('inspeccion_fecha');
            $table->unsignedBigInteger('vin_id');
            $table->foreign('vin_id')->references('vin_id')->on('vins');
            $table->unsignedBigInteger('responsable_id');
            $table->foreign('responsable_id')->references('user_id')->on('users');
            $table->unsignedBigInteger('cliente_id');
            $table->foreign('cliente_id')->references('user_id')->on('users');
            $table->unsignedBigInteger('vin_estado_inventario_id');
            $table->foreign('vin_estado_inventario_id')->references('vin_estado_inventario_id')->on('vin_estado_inventarios');
            $table->unsignedBigInteger('vin_sub_estado_inventario_id')->nullable();
            $table->foreign('vin_sub_estado_inventario_id')->references('vin_sub_estado_inventario_id')->on('vin_sub_estado_inventarios');
            $table->boolean('inspeccion_dano')->default(false);
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
        Schema::dropIfExists('inspecciones');
    }
}
