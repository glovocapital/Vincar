<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterOnceAgainToursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tours', function (Blueprint $table) {
            $table->datetime('tour_fec_hora_iniciado')->nullable();
            $table->datetime('tour_fec_fin')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tours', function (Blueprint $table) {
            $table->date('tour_fec_fin')->nullable()->change();
            $table->dropColumn('tour_fec_hora_iniciado');
        });
    }
}
