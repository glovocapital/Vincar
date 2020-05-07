<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterToursTable extends Migration
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
            $table->dropForeign('tours_salida_destino_id_foreign');
            $table->dropColumn('salida_destino_id');
            $table->dropForeign('tours_llegada_destino_id_foreign');
            $table->dropColumn('llegada_destino_id');
            $table->string('salida_destino')->nullable();
            $table->string('llegada_destino')->nullable();

            $table->unsignedBigInteger('conductor_id');
            $table->foreign('conductor_id')->references('user_id')->on('users')->onUpdate('cascade')->onDelete('cascade');

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
            $table->dropForeign('tours_conductor_id_foreign');
            $table->dropColumn('conductor_id');
            $table->unsignedBigInteger('salida_destino_id');
            $table->foreign('salida_destino_id')->references('destino_id')->on('destinos')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('llegada_destino_id');
            $table->foreign('llegada_destino_id')->references('destino_id')->on('destinos')->onUpdate('cascade')->onDelete('cascade');
            $table->dropColumn('salida_destino');
            $table->dropColumn('llegada_destino');
            $table->string('tour_guia')->change();
            $table->date('tour_fec_inicio')->change();
            $table->date('tour_fec_fin')->change();
            $table->boolean('tour_finalizado')->change();
        });
    }
}
