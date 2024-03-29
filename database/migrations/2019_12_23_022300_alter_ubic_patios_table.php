<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterUbicPatiosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ubic_patios', function (Blueprint $table) {
            $table->unsignedBigInteger('bloque_id');
            $table->foreign('bloque_id')->references('bloque_id')->on('bloques')->onUpdate('cascade')->onDelete('cascade');;
            $table->dropForeign('ubic_patios_patio_id_foreign');
            $table->dropColumn('patio_id');
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
        Schema::table('ubic_patios', function (Blueprint $table) {
            $table->unsignedBigInteger('patio_id')->nullable();
            $table->foreign('patio_id')->references('patio_id')->on('patios');
            $table->dropForeign('ubic_patios_bloque_id_foreign');
            $table->dropColumn('bloque_id');
            $table->dropColumn('deleted_at');
        });
    }
}
