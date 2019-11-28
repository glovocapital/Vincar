<?php

use Illuminate\Database\Seeder;

class VinSubEstadoInventarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('vin_sub_estado_inventarios')->insert([
            'vin_sub_estado_inventario_desc' => 'En patio',
            'vin_estado_inventario_id' => '4',
        ]);
        
        DB::table('vin_sub_estado_inventarios')->insert([
            'vin_sub_estado_inventario_desc' => 'Tránsito',
            'vin_estado_inventario_id' => '4',
        ]);

        DB::table('vin_sub_estado_inventarios')->insert([
            'vin_sub_estado_inventario_desc' => 'DyP',
            'vin_estado_inventario_id' => '4',
        ]);

        DB::table('vin_sub_estado_inventarios')->insert([
            'vin_sub_estado_inventario_desc' => 'En patio',
            'vin_estado_inventario_id' => '5',
        ]);
        
        DB::table('vin_sub_estado_inventarios')->insert([
            'vin_sub_estado_inventario_desc' => 'Tránsito',
            'vin_estado_inventario_id' => '5',
        ]);

        DB::table('vin_sub_estado_inventarios')->insert([
            'vin_sub_estado_inventario_desc' => 'DyP',
            'vin_estado_inventario_id' => '5',
        ]);
    }
}
