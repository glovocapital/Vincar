<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\SoftDeletes;

class CreateModelosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modelos', function (Blueprint $table) {
            $table->bigIncrements('modelo_id');
            $table->unsignedBigInteger('marca_id');
            $table->foreign('marca_id')->references('marca_id')->on('marcas')->onUpdate('cascade')->onDelete('cascade');;
            $table->string('modelo_nombre');
            $table->string('modelo_tipo');
            $table->string('modelo_alias')->nullable();
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
        Schema::dropIfExists('modelos');
    }
}
