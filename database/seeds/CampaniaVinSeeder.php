<?php

use Illuminate\Database\Seeder;
use App\User;


class CampaniaVinSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('campania_vins')->insert([
        	'tipo_campania_id' => 2,
        	'campania_id' => 1,
        ]);

        DB::table('campania_vins')->insert([
        	'tipo_campania_id' => 4,
        	'campania_id' => 1,
        ]);

        DB::table('campania_vins')->insert([
        	'tipo_campania_id' => 6,
        	'campania_id' => 1,
        ]);

        DB::table('campania_vins')->insert([
        	'tipo_campania_id' => 1,
        	'campania_id' => 2,
        ]);

        DB::table('campania_vins')->insert([
        	'tipo_campania_id' => 3,
        	'campania_id' => 2,
        ]);

        DB::table('campania_vins')->insert([
        	'tipo_campania_id' => 5,
        	'campania_id' => 2,
        ]);
    }
}
