<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRutasToToursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tours', function (Blueprint $table) {
            $table->string('tour_guia')->nullable()->change();
            $table->date('tour_fec_inicio')->nullable()->change();
            $table->date('tour_fec_fin')->nullable()->change();
            $table->boolean('tour_finalizado')->nullable()->change();

            $table->unsignedBigInteger('conductor_id');
            $table->foreign('conductor_id')->references('user_id')->on('users')->onUpdate('cascade')->onDelete('cascade');

            $table->unsignedBigInteger('ruta_id');
            $table->foreign('ruta_id')->references('ruta_id')->on('rutas')->onUpdate('cascade')->onDelete('cascade');

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
            $table->dropColumn('ruta_id');
            $table->dropColumn('conductor_id');
        });
    }
}
