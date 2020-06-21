<?php

use Carbon\Carbon;
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
        	'created_at' => Carbon::now(),
        	'updated_at' => Carbon::now(),
        ]);

        DB::table('roles')->insert([
            'rol_desc' => 'Administrador',
            'created_at' => Carbon::now(),
        	'updated_at' => Carbon::now(),
        ]);

    	DB::table('roles')->insert([
        	'rol_desc' => 'Operador Logistico',
        	'created_at' => Carbon::now(),
        	'updated_at' => Carbon::now(),
    	]);

        DB::table('roles')->insert([
            'rol_desc' => 'Customer',
            'created_at' => Carbon::now(),
        	'updated_at' => Carbon::now(),
        ]);

        DB::table('roles')->insert([
            'rol_desc' => 'Transportista',
            'created_at' => Carbon::now(),
        	'updated_at' => Carbon::now(),
        ]);

        DB::table('roles')->insert([
            'rol_desc' => 'Picking',
            'created_at' => Carbon::now(),
        	'updated_at' => Carbon::now(),
        ]);

        DB::table('roles')->insert([
            'rol_desc' => 'Picking',
            'created_at' => Carbon::now(),
        	'updated_at' => Carbon::now(),
        ]);

    }
}
