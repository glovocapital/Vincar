<?php

use Illuminate\Database\Seeder;

class PiezaSubAreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('pieza_sub_areas')->insert([
        	'pieza_sub_area_desc' => '1',
        ]);
        
        DB::table('pieza_sub_areas')->insert([
        	'pieza_sub_area_desc' => '2',
        ]);
        
        DB::table('pieza_sub_areas')->insert([
        	'pieza_sub_area_desc' => '3',
        ]);
        
        DB::table('pieza_sub_areas')->insert([
        	'pieza_sub_area_desc' => '4',
        ]);
        
        DB::table('pieza_sub_areas')->insert([
        	'pieza_sub_area_desc' => '5',
        ]);
        
        DB::table('pieza_sub_areas')->insert([
        	'pieza_sub_area_desc' => '6',
        ]);
        
        DB::table('pieza_sub_areas')->insert([
        	'pieza_sub_area_desc' => '7',
        ]);
        
        DB::table('pieza_sub_areas')->insert([
        	'pieza_sub_area_desc' => '8',
        ]);
        
        DB::table('pieza_sub_areas')->insert([
        	'pieza_sub_area_desc' => '9',
        ]);
    }
}
