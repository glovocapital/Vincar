<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnRutaGuiaEntregadaToRutasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rutas', function (Blueprint $table) {
            $table->string('ruta_guia_entregada_ruta')->nullable(); // Ruta del archivo de imagen de la guía con las firmas y sellos de entrega y aceptación
            $table->boolean('ruta_guia_entregada')->default(false);
            $table->text('ruta_comentarios_entrega')->nullable();
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
            $table->dropColumn('ruta_comentarios_entrega');
            $table->dropColumn('ruta_guia_entregada');
            $table->dropColumn('ruta_guia_entregada_ruta');
        });
    }
}
