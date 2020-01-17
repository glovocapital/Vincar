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
            'marca_nombre' => 'BMW',
            'marca_codigo' => 'BMW',
        ]);
        DB::table('marcas')->insert([
            'marca_nombre' => 'Mercedes',
            'marca_codigo' => 'MCD',
        ]);
        DB::table('marcas')->insert([
            'marca_nombre' => 'Volkswagen',
            'marca_codigo' => 'VWK',
        ]);

        DB::table('marcas')->insert([
            'marca_nombre' => 'Citroen',
            'marca_codigo' => 'CIT',
        ]);

        DB::table('marcas')->insert([
            'marca_nombre' => 'Mitsubishi',
            'marca_codigo' => 'MTS',
        ]);

        DB::table('marcas')->insert([
            'marca_nombre' => 'Kia',
            'marca_codigo' => 'KIA',
        ]);

        DB::table('marcas')->insert([
            'marca_nombre' => 'Peugeot',
            'marca_codigo' => 'PGT',
        ]);

        DB::table('marcas')->insert([
            'marca_nombre' => 'DS',
            'marca_codigo' => 'DS',
        ]);

        DB::table('marcas')->insert([
            'marca_nombre' => 'Maxus',
            'marca_codigo' => 'MAX',
        ]);

        DB::table('marcas')->insert([
            'marca_nombre' => 'Renault',
            'marca_codigo' => 'REN',
    	]);
    }
}
