<?php

use Illuminate\Database\Seeder;

class VinEstadoInventarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('vin_estado_inventarios')->insert([
        	'vin_estado_inventario_desc' => 'Anunciado',
        ]);

        DB::table('vin_estado_inventarios')->insert([
        	'vin_estado_inventario_desc' => 'Arribado',
        ]);

        DB::table('vin_estado_inventarios')->insert([
        	'vin_estado_inventario_desc' => 'Tránsito',
        ]);

        DB::table('vin_estado_inventarios')->insert([
        	'vin_estado_inventario_desc' => 'En Patio Disponible para la venta',
        ]);

        DB::table('vin_estado_inventarios')->insert([
        	'vin_estado_inventario_desc' => 'Agendado para entrega',
        ]);

        DB::table('vin_estado_inventarios')->insert([
        	'vin_estado_inventario_desc' => 'En Patio No disponible para la venta',
        ]);

        DB::table('vin_estado_inventarios')->insert([
        	'vin_estado_inventario_desc' => 'Suprimido',
        ]);

        DB::table('vin_estado_inventarios')->insert([
        	'vin_estado_inventario_desc' => 'Entregado',
        ]);

    }
}
