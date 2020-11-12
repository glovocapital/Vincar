<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterNnVehiculosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nn_vehiculos', function (Blueprint $table) {
            $table->string('vin_procedencia')->nullable();
            $table->string('vin_destino')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('nn_vehiculos', function (Blueprint $table) {
            $table->dropColumn('vin_destino');
            $table->dropColumn('vin_procedencia');
        });
    }
}
