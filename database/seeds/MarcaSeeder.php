<?php

use Illuminate\Database\Seeder;

class MarcaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('marcas')->insert([
            'marca_nombre' => 'Chevrolet',
            'marca_codigo' => 'CVL',
        ]);
        DB::table('marcas')->insert([
            'marca_nombre' => 'Ford',
            'marca_codigo' => 'FRD',
        ]);
        DB::table('marcas')->insert([
            'marca_nombre' => 'Nissan',
            'marca_codigo' => 'NSN',
        ]);
        DB::table('marcas')->insert([
            'marca_nombre' => 'Toyota',
            'marca_codigo' => 'TYT',
        ]);
        DB::table('marcas')->insert([
            'marca_nombre' => 'Chery',
            'marca_codigo' => 'CHR',
        ]);
        DB::table('marcas')->insert([
            'marca_nombre' => 'VMW',
            'marca_codigo' => 'VMW',
        ]);
        DB::table('marcas')->insert([
            'marca_nombre' => 'Mercedes',
            'marca_codigo' => 'MCD',
    	]);
    }
}
