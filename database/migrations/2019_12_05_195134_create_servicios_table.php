<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiciosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('servicios', function (Blueprint $table) {
            $table->bigIncrements('servicios_id');
            $table->unsignedBigInteger('producto_id');
            $table->foreign('producto_id')->references('producto_id')->on('productos')->onDelete('cascade');;

            $table->unsignedBigInteger('cliente_id');
            $table->foreign('cliente_id')->references('empresa_id')->on('empresas')->onDelete('cascade');;

            $table->unsignedBigInteger('marca_id');
            $table->foreign('marca_id')->references('marca_id')->on('marcas')->onDelete('cascade');;

            $table->unsignedBigInteger('divisa_id');
            $table->foreign('divisa_id')->references('divisa_id')->on('divisas')->onDelete('cascade');;

            $table->unsignedBigInteger('valor_asociado_id');
            $table->foreign('valor_asociado_id')->references('valor_asociado_id')->on('valores_asociados')->onDelete('cascade');;

            $table->integer('servicios_precio');
            $table->softDeletes();
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
        Schema::dropIfExists('servicios');
    }
}
