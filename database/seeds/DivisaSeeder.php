<?php

use Illuminate\Database\Seeder;

class DivisaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('divisas')->insert([
        	'divisa_tipo' => 'USD',
        ]);
        DB::table('divisas')->insert([
        	'divisa_tipo' => 'CLP',
        ]);
        DB::table('divisas')->insert([
        	'divisa_tipo' => 'UF',
        ]);
        DB::table('divisas')->insert([
        	'divisa_tipo' => 'UTM',
        ]);
        DB::table('divisas')->insert([
        	'divisa_tipo' => 'EUR',
        ]);
    }
}
