<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCamionMarcaColumnOnCamionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE camiones ALTER COLUMN camion_marca TYPE bigint USING (camion_marca::bigint)');

        Schema::table('camiones', function (Blueprint $table) {
            $table->unsignedBigInteger('camion_marca')->nullable()->change();
            $table->foreign('camion_marca')->references('marca_id')->on('marcas')->onUpdate('cascade')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('camiones', function (Blueprint $table) {
            $table->dropForeign(['camion_marca']);
            $table->string('camion_marca')->change();
        });
        DB::statement('ALTER TABLE camiones ALTER COLUMN camion_marca TYPE varchar USING (camion_marca::varchar)');
    }
}
