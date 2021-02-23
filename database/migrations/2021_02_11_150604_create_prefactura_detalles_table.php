<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrefacturaDetallesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prefactura_detalles', function (Blueprint $table) {
            $table->bigIncrements('prefactura_detalle_id');
            $table->unsignedBigInteger('prefactura_detalle_prefactura_id');
            $table->unsignedBigInteger('prefactura_detalle_vin_id');
            $table->date('prefactura_detalle_fecha_inicio');
            $table->date('prefactura_detalle_fecha_final');
            $table->unsignedBigInteger('prefactura_detalle_cant_dias');
            $table->float('prefactura_detalle_valor_dia', 8, 2);
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
        Schema::dropIfExists('prefactura_detalles');
    }
}
