<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePredespachosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('predespachos', function (Blueprint $table) {
            $table->bigIncrements('predespacho_id');

            $table->unsignedBigInteger('vin_id');
            $table->foreign('vin_id')->references('vin_id')->on('vins')->onUpdate('cascade')->onDelete('SET NULL');

            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('user_id')->on('users')->onUpdate('cascade')->onDelete('SET NULL');

            $table->unsignedBigInteger('responsable_id');
            $table->foreign('responsable_id')->references('user_id')->on('users')->onUpdate('cascade')->onDelete('SET NULL');

            $table->smallInteger('tipo_agendamiento_id')->default(1);

            $table->string('predespacho_origen')->nullable();
            $table->string('predespacho_destino')->nullable();
            
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
        Schema::dropIfExists('predespachos');
    }
}
