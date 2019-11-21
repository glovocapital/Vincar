<?php

use Illuminate\Database\Seeder;

class TipoProveedorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tipo_proveedores')->insert([
        	'tipo_proveedor_desc' => 'Servicios de Desarrollo',
    	]);
    }
}
