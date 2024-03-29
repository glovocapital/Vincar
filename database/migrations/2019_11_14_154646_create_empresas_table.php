<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpresasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->bigIncrements('empresa_id');
            $table->string('empresa_rut');
            $table->string('empresa_razon_social');
            $table->string('empresa_giro');
            $table->string('empresa_direccion');
            $table->string('empresa_nombre_contacto')->nullable();
            $table->string('empresa_telefono_contacto')->nullable();
            $table->string('empresa_email_contacto')->nullable();
            $table->boolean('empresa_es_proveedor');
            $table->unsignedBigInteger('pais_id');
            $table->foreign('pais_id')->references('pais_id')->on('paises')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('tipo_proveedor_id')->nullable();
            $table->foreign('tipo_proveedor_id')->references('tipo_proveedor_id')->on('tipo_proveedores')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('empresas');
    }
}
