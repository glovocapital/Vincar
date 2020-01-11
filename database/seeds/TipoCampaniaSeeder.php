<?php

use Illuminate\Database\Seeder;

class TipoCampaniaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tipo_campanias')->insert([
        	'tipo_campania_descripcion' => 'Añejamiento de baterías',
        ]);
        
        DB::table('tipo_campanias')->insert([
        	'tipo_campania_descripcion' => 'Cambio de batería',
        ]);
        
        DB::table('tipo_campanias')->insert([
        	'tipo_campania_descripcion' => 'Carga de batería',
        ]);
        
        DB::table('tipo_campanias')->insert([
        	'tipo_campania_descripcion' => 'Revisión de luces',
        ]);
        
        DB::table('tipo_campanias')->insert([
        	'tipo_campania_descripcion' => 'Estado del combustible',
        ]);

        DB::table('tipo_campanias')->insert([
        	'tipo_campania_descripcion' => 'Inspección cinturón de seguridad',
        ]);
    }
}
