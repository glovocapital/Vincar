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
        	'tipo_proveedor_desc' => 'Agricultura, Ganadería, Silvicultura y Pesca',
        ]);

        DB::table('tipo_proveedores')->insert([
        	'tipo_proveedor_desc' => 'Explotación de Minas y Canteras',
        ]);

        DB::table('tipo_proveedores')->insert([
        	'tipo_proveedor_desc' => 'Industrias Manufacturera',
        ]);

        DB::table('tipo_proveedores')->insert([
        	'tipo_proveedor_desc' => 'Suministro de Electricidad, Gas, Vapor y Aire Acondicionado',
        ]);

        DB::table('tipo_proveedores')->insert([
        	'tipo_proveedor_desc' => 'Suministro de Agua; Evacuación de Agua residuales, gestión de desechos y descontaminación',
        ]);

        DB::table('tipo_proveedores')->insert([
        	'tipo_proveedor_desc' => 'Construcción',
        ]);

        DB::table('tipo_proveedores')->insert([
        	'tipo_proveedor_desc' => 'Comercio al por Mayor y al por Menor; Reparación de Vehiculos Automotores y Motocicletas',
        ]);

        DB::table('tipo_proveedores')->insert([
        	'tipo_proveedor_desc' => 'Transporte y Almacenamiento',
        ]);

        DB::table('tipo_proveedores')->insert([
        	'tipo_proveedor_desc' => 'Actividades de Alojamiento y de Servicio de Comidas',
        ]);

        DB::table('tipo_proveedores')->insert([
        	'tipo_proveedor_desc' => 'Información y Comunicaciones',
        ]);

        DB::table('tipo_proveedores')->insert([
        	'tipo_proveedor_desc' => 'Actividades Financieras y de Seguros',
        ]);

        DB::table('tipo_proveedores')->insert([
        	'tipo_proveedor_desc' => 'Actividades Inmobiliarias',
        ]);

        DB::table('tipo_proveedores')->insert([
        	'tipo_proveedor_desc' => 'Actividades Profesionales, Cientificas y Técnicas',
        ]);

        DB::table('tipo_proveedores')->insert([
        	'tipo_proveedor_desc' => 'Actividades de Servicios Administrativos y de Apoyo',
        ]);

        DB::table('tipo_proveedores')->insert([
        	'tipo_proveedor_desc' => 'Adm. Pública y Defensa; Planes de Seguridad Social de Afiliación Obligatoria',
        ]);

        DB::table('tipo_proveedores')->insert([
        	'tipo_proveedor_desc' => 'Enseñanza',
        ]);

        DB::table('tipo_proveedores')->insert([
        	'tipo_proveedor_desc' => 'Actividades de Atención de la Salud Humana y de Asistencia Social',
        ]);

        DB::table('tipo_proveedores')->insert([
        	'tipo_proveedor_desc' => 'Actividades Artísticas, de Entretenimiento y Recreativas',
        ]);

        DB::table('tipo_proveedores')->insert([
        	'tipo_proveedor_desc' => 'Otras Actividades de Servicios',
        ]);

        DB::table('tipo_proveedores')->insert([
        	'tipo_proveedor_desc' => 'Actividades de los Hogares como Empleadores; Actividades No diferenciadas de los Hogares',
        ]);

        DB::table('tipo_proveedores')->insert([
        	'tipo_proveedor_desc' => 'Actividades de Organizaciones y Órganos Extraterritoriales',
    	]);
    }
}
