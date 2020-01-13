<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterVinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vins', function (Blueprint $table) {
            $table->unsignedBigInteger('vin_estado_inventario_id')->nullable();
            $table->foreign('vin_estado_inventario_id')->references('vin_estado_inventario_id')->on('vin_estado_inventarios')->onUpdate('cascade')->onDelete('cascade');
            // $table->string('vin_sub_estado_inventario_id')->nullable();
            $table->unsignedBigInteger('vin_sub_estado_inventario_id')->nullable();
            $table->foreign('vin_sub_estado_inventario_id')->references('vin_sub_estado_inventario_id')->on('vin_sub_estado_inventarios')->onUpdate('cascade')->onDelete('cascade');
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
            $table->dropForeign('vins_vin_estado_inventario_id_foreign');
            $table->dropColumn('vin_estado_inventario_id');
            $table->dropForeign('vins_vin_sub_estado_inventario_id_foreign');
            $table->dropColumn('vin_sub_estado_inventario_id');
        });
    }
}
