<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePreInspeccionVinFotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pre_inspeccion_vin_fotos', function (Blueprint $table) {
            $table->bigIncrements('foto_id');
            $table->timestamp('foto_fecha');
            $table->string('foto_descripcion');
            $table->string('foto_ubic_archivo');
            $table->decimal('foto_coord_lon', 10,8);
            $table->decimal('foto_coord_lat', 10,8);
            
            $table->string('vin_codigo');
            $table->foreign('vin_codigo')->references('vin_codigo')->on('nn_vehiculos')->onUpdate('cascade')->onDelete('cascade');
            
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
        Schema::dropIfExists('pre_inspeccion_vin_fotos');
    }
}
