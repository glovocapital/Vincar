<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNnVehiculosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nn_vehiculos', function (Blueprint $table) {
            $table->bigIncrements('vin_id');

            $table->string('vin_codigo')->unique();
            $table->string('vin_patente')->nullable();
            $table->string('vin_modelo');
            
            $table->unsignedBigInteger('vin_marca')->nullable();
            $table->foreign('vin_marca')->references('marca_id')->on('marcas')->onUpdate('cascade')->onDelete('SET NULL');

            $table->string('vin_color');
            $table->string('vin_motor')->nullable();
            // $table->string('vin_segmento')->nullable();
            $table->date('vin_fec_ingreso');
            
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('user_id')->on('users')->onUpdate('cascade')->onDelete('SET NULL');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nn_vehiculos');
    }
}
