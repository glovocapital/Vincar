<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConductorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conductors', function (Blueprint $table) {
            $table->bigIncrements('conductor_id');

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('user_id')->on('users')->onUpdate('cascade')->onDelete('cascade');;

            $table->unsignedBigInteger('tipo_licencia_id');
            $table->foreign('tipo_licencia_id')->references('tipo_licencia_id')->on('tipo_licencias')->onUpdate('cascade')->onDelete('cascade');;

            $table->date('conductor_fecha_vencimiento');
            $table->string('conductor_foto_documento');
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
        Schema::dropIfExists('conductors');
    }
}
