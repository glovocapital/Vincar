<?php

use Illuminate\Database\Seeder;
use App\User;


class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'user_nombre'		=>	'Alain',
            'user_apellido'		=>	'Diaz',
            'user_rut'          =>  '26506613-5',
            'user_cargo'        => 'Desarrollador',
            'user_estado'       => '1',
            'email'			    =>  'a.diaz@glovocapital.com',
            'user_telefono'     =>  '991364514',
            'password' 		    => bcrypt('123456'),
            'rol_id'            => '1',
            'empresa_id'        => '1',
        ]);

          User::create([
            'user_nombre'		=>	'Crox',
            'user_apellido'		=>	'Sanchez',
            'user_rut'          =>  '12999888-5',
            'user_cargo'        => 'Desarrollador',
            'user_estado'       => '1',
            'email'			    =>  'desarrollo@glovocapital.com',
            'user_telefono'     =>  '3233300232',
            'password' 		    => bcrypt('123456'),
            'rol_id'            => '1',
            'empresa_id'        => '1',
        ]);

        User::create([
            'user_nombre'		=>	'Abraham',
            'user_apellido'		=>	'Ferrer',
            'user_rut'          =>  '25555555-5',
            'user_cargo'        =>  'Analista',
            'user_estado'       =>  '1',
            'email'			    =>  'info@glovocapital.com',
            'user_telefono'     =>  '977763391',
            'password' 		    => bcrypt('123456'),
            'rol_id'            => '1',
            'empresa_id'        => '1',
        ]);
    }
}
