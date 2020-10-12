<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterColumnsToGuiasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE guias ALTER COLUMN guia_fecha TYPE date USING (guia_fecha::date)');
        
        Schema::table('guias', function (Blueprint $table) {
            $table->dropForeign(['empresa_id']);
            
            $table->string('guia_numero')->after('guia_id')->unique();
            $table->date('guia_fecha')->change()->after('guia_numero');
            $table->boolean('guia_carga_entregada')->after('guia_fecha')->default(false);
            
            $table->unsignedBigInteger('empresa_id')->change()->after('guia_ruta')->nullable();
            $table->foreign('empresa_id')->references('empresa_id')->on('empresas')->onUpdate('cascade')->onDelete('SET NULL');

            $table->string('guia_ruta')->change()->after('empresa_id')->nullable()->comment('Ruta de archivo en disco duro');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('guias', function (Blueprint $table) {
            $table->dropColumn('guia_numero');
            $table->dropColumn('guia_carga_entregada');
            $table->dropForeign(['empresa_id']);
            
            $table->foreign('empresa_id')->references('empresa_id')->on('empresas')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('empresa_id')->change();
            
            $table->string('guia_fecha')->change();

            $table->string('guia_ruta')->change(); // Ruta de ubicación en disco duro en el sistema donde se almacena la foto o pdf
        });

        DB::statement('ALTER TABLE guias ALTER COLUMN guia_fecha TYPE varchar USING (guia_fecha::varchar)');
    }
}
