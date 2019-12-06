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
        ]);
        DB::table('marcas')->insert([
        	'marca_nombre' => 'Ford',
        ]);
        DB::table('marcas')->insert([
        	'marca_nombre' => 'Nissan',
        ]);
        DB::table('marcas')->insert([
        	'marca_nombre' => 'Toyota',
        ]);
        DB::table('marcas')->insert([
        	'marca_nombre' => 'Chery',
        ]);
        DB::table('marcas')->insert([
        	'marca_nombre' => 'VMW',
        ]);
        DB::table('marcas')->insert([
        	'marca_nombre' => 'Mercedes',
    	]);
    }
}
