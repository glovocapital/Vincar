<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVinSubEstadoInventariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vin_sub_estado_inventarios', function (Blueprint $table) {
            $table->bigIncrements('vin_sub_estado_inventario_id');
            $table->string('vin_sub_estado_inventario_desc');
            $table->unsignedBigInteger('vin_estado_inventario_id');
            $table->foreign('vin_estado_inventario_id')->references('vin_estado_inventario_id')->on('vin_estado_inventarios');
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
        Schema::dropIfExists('vin_sub_estado_inventarios');
    }
}
