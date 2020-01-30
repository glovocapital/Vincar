<?php

use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::table('roles')->insert([
        	'rol_desc' => 'SuperAdministrador',
        ]);

        DB::table('roles')->insert([
            'rol_desc' => 'Administrador',
        ]);

    	DB::table('roles')->insert([
        	'rol_desc' => 'Operador Logistico',
    	]);

        DB::table('roles')->insert([
            'rol_desc' => 'Customer',
        ]);

        DB::table('roles')->insert([
            'rol_desc' => 'Transportista',
        ]);

        DB::table('roles')->insert([
            'rol_desc' => 'Picking',
        ]);

    }
}
