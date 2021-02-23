<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrefacturaDescuentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prefactura_descuentos', function (Blueprint $table) {
            $table->bigIncrements('prefactura_descuento_id');

            $table->unsignedBigInteger('prefactura_descuento_prefactura_id');
            $table->string('prefactura_descuento_tipo');
            $table->double('prefactura_descuento_monto');
            $table->string('prefactura_descuento_motivo');
            $table->unsignedBigInteger('prefactura_descuento_user_id');

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
        Schema::dropIfExists('prefactura_descuentos');
    }
}
