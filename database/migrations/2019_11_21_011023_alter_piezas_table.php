<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPiezasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('piezas', function (Blueprint $table) {
            $table->unsignedBigInteger('pieza_sub_area_id');
            $table->foreign('pieza_sub_area_id')->references('pieza_sub_area_id')->on('pieza_sub_areas');
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
            $table->dropForeign('piezas_pieza_sub_area_id_foreign');
            $table->dropColumn('pieza_sub_area_id');
        });
    }
}
