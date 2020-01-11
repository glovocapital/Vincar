<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterViajesFalsosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('viajes_falsos', function (Blueprint $table) {
            $table->unsignedBigInteger('camion_id');
            $table->foreign('camion_id')->references('camion_id')->on('camiones')->onUpdate('cascade')->onDelete('cascade');;
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('viajes_falsos', function (Blueprint $table) {
            $table->dropForeign('viajes_falsos_camion_id_foreign');
            $table->dropColumn('camion_id');
            
        });
    }
}
