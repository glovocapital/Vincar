<?php

use App\Campania;
use Illuminate\Database\Seeder;


class CampaniaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Campania::create([
            'campania_fecha_finalizacion'		=>	'2020-02-02',
            'campania_observaciones'		=>	'Campaña de prueba',
            'vin_id'          =>  1,
            'user_id'        => 2,
        ]);

        Campania::create([
            'campania_fecha_finalizacion'		=>	'2020-02-03',
            'campania_observaciones'		=>	'Campaña 2 de prueba',
            'vin_id'          =>  2,
            'user_id'        => 1,
        ]);
    }
}
