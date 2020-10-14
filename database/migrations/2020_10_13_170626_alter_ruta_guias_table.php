<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterRutaGuiasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ruta_guias', function (Blueprint $table) {
            $table->renameColumn('ruta_guias_id', 'ruta_guia_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ruta_guias', function (Blueprint $table) {
            $table->renameColumn('ruta_guia_id', 'ruta_guias_id');
        });
    }
}
