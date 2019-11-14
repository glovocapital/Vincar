<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('rol_id');
            $table->foreign('rol_id')->references('rol_id')->on('roles');
            $table->unsignedBigInteger('empresa_id');
            $table->foreign('empresa_id')->references('empresa_id')->on('empresas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['rol_id']);
            $table->dropForeign(['empresa_id']);
        });
    }
}
