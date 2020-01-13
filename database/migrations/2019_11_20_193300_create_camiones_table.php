<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class CreateCamionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('camiones', function (Blueprint $table) {
            $table->bigIncrements('camion_id');
            $table->string('camion_patente');
            $table->string('camion_modelo');
            $table->string('camion_marca');
            $table->integer('camion_anio');
            $table->date('camion_fecha_revision');
            $table->date('camion_fecha_circulacion');
            $table->string('camion_foto_documentos')->nullable();


            $table->unsignedBigInteger('empresa_id');
            $table->foreign('empresa_id')->references('empresa_id')->on('empresas')->onDelete('cascade')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
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
        Schema::dropIfExists('camiones');
    }
}
