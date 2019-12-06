<?php

use Illuminate\Database\Seeder;

class TipoDanoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tipo_danos')->insert([
        	'tipo_dano_descripcion' => 'Abolladura',
        ]);
        
        DB::table('tipo_danos')->insert([
        	'tipo_dano_descripcion' => 'Raspón',
        ]);
        
        DB::table('tipo_danos')->insert([
        	'tipo_dano_descripcion' => 'Rotura',
        ]);
        
        DB::table('tipo_danos')->insert([
        	'tipo_dano_descripcion' => 'Decoloración',
        ]);
        
        DB::table('tipo_danos')->insert([
        	'tipo_dano_descripcion' => 'Rayón',
        ]);
    }
}
