<?php

use Illuminate\Database\Seeder;

class GravedadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('gravedades')->insert([
        	'gravedad_descripcion' => 'Leve',
        ]);
        
        DB::table('gravedades')->insert([
        	'gravedad_descripcion' => 'Moderada',
        ]);
        
        DB::table('gravedades')->insert([
        	'gravedad_descripcion' => 'Grave',
        ]);
    }
}
