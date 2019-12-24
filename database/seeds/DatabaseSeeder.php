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
        $this->call(VinEstadoInventarioSeeder::class);
        $this->call(VinSubEstadoInventarioSeeder::class);
        $this->call(TipoDanoSeeder::class);
        $this->call(GravedadSeeder::class);
        $this->call(PiezaSubAreaSeeder::class);
        $this->call(CategoriaPiezaSeeder::class);
        $this->call(SubcategoriaPiezaSeeder::class);
        $this->call(PiezaSeeder::class);
        $this->call(MarcaSeeder::class);
        $this->call(DivisaSeeder::class);
        $this->call(ValorAsociadoSeeder::class);
        $this->call(TipoLicenciaSeeder::class);
        $this->call(ChileTableSeeder::class);

        // $this->call(UsersTableSeeder::class);
    }
}
