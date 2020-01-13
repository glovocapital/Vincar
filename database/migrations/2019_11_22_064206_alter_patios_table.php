<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPatiosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('patios', function (Blueprint $table) {
            $table->unsignedBigInteger('region_id');
            $table->foreign('region_id')->references('region_id')->on('regiones')->onUpdate('cascade')->onDelete('cascade');
            // $table->unsignedBigInteger('provincia_id');
            // $table->foreign('provincia_id')->references('provincia_id')->on('provincias');
            $table->unsignedBigInteger('comuna_id');
            $table->foreign('comuna_id')->references('comuna_id')->on('comunas')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('patios', function (Blueprint $table) {
            $table->dropForeign('patios_region_id_foreign');
            $table->dropColumn('region_id');
            // $table->dropForeign('patios_provincia_id_foreign');
            // $table->dropColumn('provincia_id');
            $table->dropForeign('patios_comuna_id_foreign');
            $table->dropColumn('comuna_id');
        });
    }
}
