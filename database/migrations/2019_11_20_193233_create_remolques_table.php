<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRemolquesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('remolques', function (Blueprint $table) {
            $table->bigIncrements('remolque_id');
            $table->string('remolque_patente');
            $table->string('remolque_marca');
            $table->integer('remolque_anio');
            $table->string('remolque_modelo');
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
        Schema::dropIfExists('remolques');
    }
}
