<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterGuiaVinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('guia_vins', function (Blueprint $table) {
            $table->renameColumn('guia_vins_id', 'guia_vin_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('guia_vins', function (Blueprint $table) {
            $table->renameColumn('guia_vin_id', 'guia_vins_id');
        });
    }
}
