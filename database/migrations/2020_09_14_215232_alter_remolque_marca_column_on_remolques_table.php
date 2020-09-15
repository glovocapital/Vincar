<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterRemolqueMarcaColumnOnRemolquesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE remolques ALTER COLUMN remolque_marca TYPE bigint USING (remolque_marca::bigint)');

        Schema::table('remolques', function (Blueprint $table) {
            $table->unsignedBigInteger('remolque_marca')->nullable()->change();
            $table->foreign('remolque_marca')->references('marca_id')->on('marcas')->onUpdate('cascade')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('remolques', function (Blueprint $table) {
            $table->dropForeign(['remolque_marca']);
            $table->string('remolque_marca')->change();
        });
        DB::statement('ALTER TABLE remolques ALTER COLUMN remolque_marca TYPE varchar USING (remolque_marca::varchar)');
    }
}
