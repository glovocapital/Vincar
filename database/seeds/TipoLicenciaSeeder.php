<?php

use Illuminate\Database\Seeder;

class TipoLicenciaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tipo_licencias')->insert([
        	'tipo_licencia_nombre' => 'A1',
        ]);
        DB::table('tipo_licencias')->insert([
        	'tipo_licencia_nombre' => 'A2',
        ]);
        DB::table('tipo_licencias')->insert([
        	'tipo_licencia_nombre' => 'A3',
        ]);
        DB::table('tipo_licencias')->insert([
        	'tipo_licencia_nombre' => 'A4',
        ]);
        DB::table('tipo_licencias')->insert([
        	'tipo_licencia_nombre' => 'A5',
        ]);
        DB::table('tipo_licencias')->insert([
        	'tipo_licencia_nombre' => 'B',
        ]);
        DB::table('tipo_licencias')->insert([
        	'tipo_licencia_nombre' => 'C',
        ]);
        DB::table('tipo_licencias')->insert([
        	'tipo_licencia_nombre' => 'D',
        ]);
        DB::table('tipo_licencias')->insert([
        	'tipo_licencia_nombre' => 'E',
        ]);
        DB::table('tipo_licencias')->insert([
        	'tipo_licencia_nombre' => 'F',
    	]);
    }
}
