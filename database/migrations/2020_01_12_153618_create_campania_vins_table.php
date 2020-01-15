<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampaniaVinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campania_vins', function (Blueprint $table) {
            $table->bigIncrements('campania_vin_id');

            $table->unsignedBigInteger('tipo_campania_id');
            $table->foreign('tipo_campania_id')->references('tipo_campania_id')->on('tipo_campanias')->onUpdate('cascade')->onDelete('cascade');
            
            $table->unsignedBigInteger('campania_id');
            $table->foreign('campania_id')->references('campania_id')->on('campanias')->onUpdate('cascade')->onDelete('cascade');

            $table->softDeletes();
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
        Schema::dropIfExists('campania_vins');
    }
}
