<?php

use Illuminate\Database\Seeder;
use App\User;


class VinsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Vin::create([
            'vin_codigo'		=>	'JTMZ43FV9LD025878',
            'vin_patente'		=>	'ABC12345TX',
            'vin_modelo'          =>  'Rav4',
            'vin_marca'        => 'Toyota',
            'vin_color'       => 'Gris oscuro metálico',
            'vin_motor'			    =>  '88LD025878',
            'vin_segmento'     =>  'SUV',
            'vin_fec_ingreso' 		    => '2019-12-01',
            'user_id'            => '1',
            'vin_estado_inventario_id'        => '1',
            'vin_sub_estado_inventario_id'        => null,
        ]);

        Vin::create([
            'vin_codigo'		=>	'KLVZ43FV9LD010104',
            'vin_patente'		=>	'DEF98765ZY',
            'vin_modelo'          =>  'Yaris',
            'vin_marca'        => 'Toyota',
            'vin_color'       => 'Azul',
            'vin_motor'			    =>  '77KC914767',
            'vin_segmento'     =>  'Coupe',
            'vin_fec_ingreso' 		    => '2020-01-02',
            'user_id'            => '2',
            'vin_estado_inventario_id'        => '1',
            'vin_sub_estado_inventario_id'        => null,
        ]);
    }
}
