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
            'empresa_email_contacto' => 'info@glovocapital.com',
            'pais_id' => '4',
            'tipo_proveedor_id' => '1',

        ]);
        
        DB::table('empresas')->insert([
        	'empresa_rut' => '11111111-1',
            'empresa_razon_social' => 'Empresa de Prueba 1',
            'empresa_giro' => 'Marca Automotriz',
            'empresa_direccion' =>  'Providencia - Santiago de Chile',
            'empresa_nombre_contacto' => 'Fulano de Tal',
            'empresa_es_proveedor' => '0',
            'empresa_telefono_contacto' => '555555555',
            'empresa_email_contacto' => 'contacto@email_ficticio.com',
            'pais_id' => '4',
        ]);
        
        DB::table('empresas')->insert([
        	'empresa_rut' => '22222222-2',
            'empresa_razon_social' => 'Empresa de Prueba 2',
            'empresa_giro' => 'Marca Automotriz',
            'empresa_direccion' =>  'Providencia - Santiago de Chile',
            'empresa_nombre_contacto' => 'Sultano de Tal',
            'empresa_es_proveedor' => '0',
            'empresa_telefono_contacto' => '666666666',
            'empresa_email_contacto' => 'contacto2@otro_email_ficticio.com',
            'pais_id' => '4',
    	]);
    }
}
