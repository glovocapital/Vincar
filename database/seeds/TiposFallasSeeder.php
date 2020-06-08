<?php

use Illuminate\Database\Seeder;

class TiposFallasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tipos_fallas')->insert([
        	'tipos_fallas_descripcion' => 'Sin combustible',
        ]);

        DB::table('tipos_fallas')->insert([
        	'tipos_fallas_descripcion' => 'Sin carga de bateria',
        ]);

        DB::table('tipos_fallas')->insert([
        	'tipos_fallas_descripcion' => 'Sin aire en los neumáticos',
        ]);

        DB::table('tipos_fallas')->insert([
        	'tipos_fallas_descripcion' => 'Fallas mecanicas',
        ]);
    }
}
