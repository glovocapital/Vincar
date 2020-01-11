<?php

use Illuminate\Database\Seeder;

class TipoTareaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tipo_tareas')->insert([
        	'tipo_tarea_descripcion' => 'Picking',
        ]);
        
        DB::table('tipo_tareas')->insert([
        	'tipo_tarea_descripcion' => 'Limpieza',
        ]);
        
        DB::table('tipo_tareas')->insert([
        	'tipo_tarea_descripcion' => 'Campaña',
        ]);
        
        DB::table('tipo_tareas')->insert([
        	'tipo_tarea_descripcion' => 'Inspección',
        ]);

        DB::table('tipo_tareas')->insert([
        	'tipo_tarea_descripcion' => 'Parking',
        ]);
    }
}
