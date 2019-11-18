<?php

use Illuminate\Database\Seeder;

class EmpresaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('empresas')->insert([
        	'empresa_rut' => '76935467-0',
            'empresa_razon_social' => 'Glovocapital SPA',
            'empresa_giro' => 'Consultoria y Desarrollo',
            'empresa_direccion' =>  'Providencia - Santiago de Chile',
            'empresa_nombre_contacto' => 'Oscar Ferrer',
            'empresa_es_proveedor' => '1',
            'empresa_telefono_contacto' => '977763391',
            'pais_id' => '1',
            'tipo_proveedor_id' => '1',

    	]);
    }
}
