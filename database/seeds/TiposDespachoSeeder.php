<?php

use Illuminate\Database\Seeder;

class TiposDespachoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tipos_despachos')->insert([
        	'tipos_despachos_descripcion' => 'Por tierra',
        ]);

        DB::table('tipos_despachos')->insert([
        	'tipos_despachos_descripcion' => 'En remolque',
        ]);
    }
}
