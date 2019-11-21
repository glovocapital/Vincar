<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fotos', function (Blueprint $table) {
            $table->bigIncrements('foto_id');
            $table->timestamp('foto_fecha');
            $table->string('foto_descripcion');
            $table->string('foto_ubic_archivo');
            $table->decimal('foto_coord_lon', 10,8);
            $table->decimal('foto_coord_lat', 10,8);
            $table->unsignedBigInteger('dano_pieza_id');
            $table->foreign('dano_pieza_id')->references('dano_pieza_id')->on('dano_piezas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fotos');
    }
}
