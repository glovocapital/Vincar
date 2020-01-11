<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubcategoriaPiezasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subcategoria_piezas', function (Blueprint $table) {
            $table->bigIncrements('subcategoria_pieza_id');
            $table->string('subcategoria_pieza_desc');
            $table->unsignedBigInteger('categoria_pieza_id');
            $table->foreign('categoria_pieza_id')->references('categoria_pieza_id')->on('categoria_piezas')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('subcategoria_piezas');
    }
}
