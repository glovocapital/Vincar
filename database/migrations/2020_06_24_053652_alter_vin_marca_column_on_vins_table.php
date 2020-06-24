<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlterVinMarcaColumnOnVinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE vins ALTER COLUMN vin_marca TYPE bigint USING (vin_marca::bigint)');

        Schema::table('vins', function (Blueprint $table) {
            $table->unsignedBigInteger('vin_marca')->change();
            $table->foreign('vin_marca')->references('marca_id')->on('marcas')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vins', function (Blueprint $table) {
            $table->dropForeign('vins_vin_marca_foreign');
            $table->bigInteger('vin_marca')->change();
        });
        DB::statement('ALTER TABLE vins ALTER COLUMN vin_marca TYPE varchar USING (vin_marca::varchar)');
    }
}
