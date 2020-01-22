<?php

use Illuminate\Database\Seeder;

class PaisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('paises')->insert([
        	'pais_nombre' => 'Argentina',
        ]);


        DB::table('paises')->insert([
        	'pais_nombre' => 'Brasil',
        ]);

        DB::table('paises')->insert([
        	'pais_nombre' => 'Bolivia',
        ]);

        DB::table('paises')->insert([
        	'pais_nombre' => 'Chile',
        ]);

        DB::table('paises')->insert([
        	'pais_nombre' => 'Colombia',
        ]);


        DB::table('paises')->insert([
        	'pais_nombre' => 'Ecuador',
        ]);


        DB::table('paises')->insert([
        	'pais_nombre' => 'Paraguay',
        ]);


        DB::table('paises')->insert([
        	'pais_nombre' => 'Perú',
        ]);

        DB::table('paises')->insert([
        	'pais_nombre' => 'Uruguay',
        ]);

        DB::table('paises')->insert([
        	'pais_nombre' => 'Venezuela',
        ]);
    }
}
