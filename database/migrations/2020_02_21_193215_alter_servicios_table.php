<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterServiciosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('servicios', function (Blueprint $table) {

            $table->unsignedBigInteger('marca_id')->nullable()->change();

            $table->unsignedBigInteger('caracteristica_vin_id');
            $table->foreign('caracteristica_vin_id')->references('caracteristica_vin_id')->on('caracteristica_vins')->onUpdate('cascade')->onDelete('cascade')->nullable();

            $table->decimal('servicios_precio', 8, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('servicios', function (Blueprint $table) {
            $table->dropColumn('caracteristica_vin_id');
        });
    }
}
