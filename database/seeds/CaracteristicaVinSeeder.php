<?php

use Illuminate\Database\Seeder;

class CaracteristicaVinSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('caracteristica_vins')->insert([
        	'caracteristica_vin_nombre' => 'S',
        ]);

        DB::table('caracteristica_vins')->insert([
        	'caracteristica_vin_nombre' => 'M',
        ]);

        DB::table('caracteristica_vins')->insert([
        	'caracteristica_vin_nombre' => 'L',
        ]);

        DB::table('caracteristica_vins')->insert([
        	'caracteristica_vin_nombre' => 'XL',
        ]);

        DB::table('caracteristica_vins')->insert([
        	'caracteristica_vin_nombre' => 'Mini Bus',
        ]);

        DB::table('caracteristica_vins')->insert([
        	'caracteristica_vin_nombre' => 'Bus',
        ]);

        DB::table('caracteristica_vins')->insert([
        	'caracteristica_vin_nombre' => 'Camión',
        ]);
    }
}
