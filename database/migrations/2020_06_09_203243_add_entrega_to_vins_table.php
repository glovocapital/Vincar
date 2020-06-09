<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEntregaToVinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vins', function (Blueprint $table) {
            $table->date('vin_fecha_entrega')->nullable();
            $table->date('vin_fecha_agendado')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vins', function (Blueprint $table) {
            $table->dropColumn('vin_fecha_entrega');
            $table->dropColumn('vin_fecha_agendado');

        });
    }
}
