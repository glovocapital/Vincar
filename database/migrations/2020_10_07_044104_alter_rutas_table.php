<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterRutasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rutas', function (Blueprint $table) {
            $table->string('ruta_en_origen')->nullable()->after('ruta_guia');
            $table->dateTime('ruta_fecha_en_origen')->nullable()->after('ruta_en_origen');

            $table->string('ruta_ubicacion_actual')->nullable()->after('ruta_fecha_en_origen');
            $table->dateTime('ruta_fecha_ubicacion_actual')->nullable()->after('ruta_ubicacion_actual');

            $table->boolean('ruta_finalizada')->after('ruta_fecha_ubicacion_actual')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rutas', function (Blueprint $table) {
            $table->dropColumn('ruta_finalizada');

            $table->dropColumn('ruta_fecha_ubicacion_actual');
            $table->dropColumn('ruta_ubicacion_actual');

            $table->dropColumn('ruta_fecha_en_origen');
            $table->dropColumn('ruta_en_origen');
        });
    }
}
