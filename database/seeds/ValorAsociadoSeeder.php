<?php

use Illuminate\Database\Seeder;

class ValorAsociadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('valores_asociados')->insert([
        	'valor_asociado_tipo' => 'Dia',
        ]);
        DB::table('valores_asociados')->insert([
        	'valor_asociado_tipo' => 'Servicio',
        ]);
    }
}
