<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoricoVinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('historico_vins', function (Blueprint $table) {
            $table->bigIncrements('historico_vin_id');

            $table->unsignedBigInteger('vin_id');
            $table->foreign('vin_id')->references('vin_id')->on('vins')->onUpdate('cascade')->onDelete('cascade');
            
            $table->unsignedBigInteger('vin_estado_inventario_id');
            $table->foreign('vin_estado_inventario_id')->references('vin_estado_inventario_id')->on('vin_estado_inventarios')->onUpdate('cascade')->onDelete('cascade');

            $table->date('historico_vin_fecha');

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('user_id')->on('users')->onUpdate('cascade')->onDelete('cascade');

            $table->unsignedBigInteger('origen_id')->nullable();
            $table->foreign('origen_id')->references('bloque_id')->on('bloques')->onUpdate('cascade')->onDelete('cascade');
            
            $table->unsignedBigInteger('destino_id')->nullable();
            $table->foreign('destino_id')->references('bloque_id')->on('bloques')->onUpdate('cascade')->onDelete('cascade');
            
            $table->unsignedBigInteger('empresa_id');
            $table->foreign('empresa_id')->references('empresa_id')->on('empresas')->onUpdate('cascade')->onDelete('cascade');

            $table->text('historico_vin_descripcion')->nullable();
            
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
        Schema::dropIfExists('historico_vins');
    }
}
