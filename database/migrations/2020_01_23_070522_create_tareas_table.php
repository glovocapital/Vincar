<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTareasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tareas', function (Blueprint $table) {
            $table->bigIncrements('tarea_id');

            $table->date('tarea_fecha_finalizacion');
            $table->integer('tarea_prioridad')->default(0);
            $table->time('tarea_hora_termino');
            
            $table->unsignedBigInteger('vin_id');
            $table->foreign('vin_id')->references('vin_id')->on('vins')->onUpdate('cascade')->onDelete('cascade');
            
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('user_id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            
            $table->unsignedBigInteger('tipo_tarea_id');
            $table->foreign('tipo_tarea_id')->references('tipo_tarea_id')->on('tipo_tareas')->onUpdate('cascade')->onDelete('cascade');
            
            $table->unsignedBigInteger('tipo_destino_id');
            $table->foreign('tipo_destino_id')->references('tipo_destino_id')->on('tipo_destinos')->onUpdate('cascade')->onDelete('cascade');
            
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
        Schema::dropIfExists('tareas');
    }
}
