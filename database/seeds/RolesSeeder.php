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
        	'rol_desc' => 'Cliente',
    	]);

        DB::table('roles')->insert([
            'rol_desc' => 'Operador',
        ]);

        DB::table('roles')->insert([
            'rol_desc' => 'Conductor',
        ]);

        DB::table('roles')->insert([
            'rol_desc' => 'Pickero',
        ]);

    }
}
