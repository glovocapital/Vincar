<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSubcategoriaIdToPiezasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('piezas', function (Blueprint $table) {
            $table->unsignedBigInteger('subcategoria_pieza_id');
            $table->foreign('subcategoria_pieza_id')->references('subcategoria_pieza_id')->on('subcategoria_piezas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('piezas', function (Blueprint $table) {
            $table->dropForeign('piezas_pieza_subcategoria_pieza_id_foreign');
            $table->dropColumn('subcategoria_pieza_id');
        });
    }
}
