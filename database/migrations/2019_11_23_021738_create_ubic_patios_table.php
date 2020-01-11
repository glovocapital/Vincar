<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUbicPatiosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ubic_patios', function (Blueprint $table) {
            $table->bigIncrements('ubic_patio_id');
            $table->string('ubic_patio_fila');
            $table->string('ubic_patio_columna');
            $table->boolean('ubic_patio_ocupada')->default(false);
            $table->unsignedBigInteger('vin_id')->nullable()->default(null);
            $table->foreign('vin_id')->references('vin_id')->on('vins')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('patio_id');
            $table->foreign('patio_id')->references('patio_id')->on('patios')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('ubic_patios');
    }
}
