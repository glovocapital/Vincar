<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDivisasValorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('divisas_valor', function (Blueprint $table) {
            $table->bigIncrements('divisa_valor_id');

            $table->unsignedBigInteger('divisa_id');
            $table->foreign('divisa_id')->references('divisa_id')->on('divisas')->onUpdate('cascade')->onDelete('cascade');

            $table->date('divisa_valor_fecha');
            $table->decimal('divisa_valor_valor', 8, 2);

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
        Schema::dropIfExists('divisas_valor');
    }
}
