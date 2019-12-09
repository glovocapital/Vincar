<?php

use Illuminate\Database\Seeder;

class CategoriaPiezaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categoria_piezas')->insert([
        	'categoria_pieza_desc' => 'Carrocería',
        ]);
        
        DB::table('categoria_piezas')->insert([
        	'categoria_pieza_desc' => 'Motor',
        ]);
        
        DB::table('categoria_piezas')->insert([
            'categoria_pieza_desc' => 'Sistema de Transmisión',
        ]);

        DB::table('categoria_piezas')->insert([
            'categoria_pieza_desc' => 'Sistema de Frenos',
        ]);

        DB::table('categoria_piezas')->insert([
            'categoria_pieza_desc' => 'Sistema Eléctrico',
        ]);

        DB::table('categoria_piezas')->insert([
            'categoria_pieza_desc' => 'Sistema de Dirección',
        ]);
        
        DB::table('categoria_piezas')->insert([
            'categoria_pieza_desc' => 'Sistema de Suspensión',
        ]);
        
        DB::table('categoria_piezas')->insert([
        	'categoria_pieza_desc' => 'Interior Vehículo',
        ]);

        DB::table('categoria_piezas')->insert([
        	'categoria_pieza_desc' => 'Accesorios en General',
        ]);
    }
}
