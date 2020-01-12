<?php

use Illuminate\Database\Seeder;

class TipoDestinoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tipo_destinos')->insert([
        	'tipo_destino_descripcion' => 'Aparcadero',
        ]);
        
        DB::table('tipo_destinos')->insert([
        	'tipo_destino_descripcion' => 'Equipamiento',
        ]);
        
        DB::table('tipo_destinos')->insert([
        	'tipo_destino_descripcion' => 'Galpón PDI',
        ]);
        
        DB::table('tipo_destinos')->insert([
        	'tipo_destino_descripcion' => 'Lavadero',
        ]);
        
        DB::table('tipo_destinos')->insert([
        	'tipo_destino_descripcion' => 'Oficina',
        ]);

        DB::table('tipo_destinos')->insert([
        	'tipo_destino_descripcion' => 'Temporal',
        ]);

        DB::table('tipo_destinos')->insert([
        	'tipo_destino_descripcion' => 'Zona arribo',
        ]);

        DB::table('tipo_destinos')->insert([
        	'tipo_destino_descripcion' => 'Zona despacho',
        ]);

        DB::table('tipo_destinos')->insert([
        	'tipo_destino_descripcion' => 'Zona taller',
        ]);
    }
}
