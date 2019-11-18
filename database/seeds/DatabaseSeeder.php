<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $this->call(RolesSeeder::class);
        $this->call(TipoProveedorSeeder::class);
        $this->call(PaisSeeder::class);
        $this->call(EmpresaSeeder::class);
        $this->call(UsersSeeder::class);
        // $this->call(UsersTableSeeder::class);
    }
}
