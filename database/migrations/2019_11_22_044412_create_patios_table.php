<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatiosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patios', function (Blueprint $table) {
            $table->bigIncrements('patio_id');
            $table->string('patio_nombre');
            $table->string('patio_bloques');
            $table->decimal('patio_coord_lon', 10,8);
            $table->decimal('patio_coord_lat', 10,8);
            $table->string('patio_direccion');
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
        Schema::dropIfExists('patios');
    }
}
