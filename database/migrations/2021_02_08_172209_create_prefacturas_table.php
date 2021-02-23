<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrefacturasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prefacturas', function (Blueprint $table) {
            $table->bigIncrements('prefactura_id');

            $table->unsignedBigInteger('prefactura_empresa_id');
            $table->date('prefactura_fecha_inicio');
            $table->date('prefactura_fecha_final');
            $table->unsignedBigInteger('prefactura_user_id_creacion');
            $table->unsignedBigInteger('prefactura_user_id_actualizacion');
            $table->unsignedBigInteger('prefactura_numero_orden');


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
        Schema::dropIfExists('prefacturas');
    }
}
