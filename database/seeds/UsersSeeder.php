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

        User::create([
            'user_nombre'		=>	'David',
            'user_apellido'		=>	'Segura',
            'user_rut'          =>  '99881212-4',
            'user_cargo'        =>  'Desarrollador',
            'user_estado'       =>  '1',
            'email'			    =>  'asthar2010@gmail.com',
            'user_telefono'     =>  '04129876534',
            'password' 		    => '123456',
            'rol_id'            => '1',
            'empresa_id'        => '1',
        ]);

        User::create([
            'user_nombre'		=>	'José',
            'user_apellido'		=>	'Pérez',
            'user_rut'          =>  '36666666-6',
            'user_cargo'        =>  'Operador',
            'user_estado'       =>  '1',
            'email'			    =>  'email1@email_falso.com',
            'user_telefono'     =>  '9876543210',
            'password' 		    => bcrypt('123456'),
            'rol_id'            => '4',
            'empresa_id'        => '2',
        ]);

        User::create([
            'user_nombre'		=>	'Eduardo',
            'user_apellido'		=>	'Rodríguez',
            'user_rut'          =>  '47777777-7',
            'user_cargo'        =>  'Conductor',
            'user_estado'       =>  '1',
            'email'			    =>  'email2@email_falso.com',
            'user_telefono'     =>  '97875643210',
            'password' 		    => bcrypt('123456'),
            'rol_id'            => '5',
            'empresa_id'        => '2',
        ]);

        User::create([
            'user_nombre'		=>	'Antonio',
            'user_apellido'		=>	'Dugarte',
            'user_rut'          =>  '58888888-8',
            'user_cargo'        =>  'Pickero',
            'user_estado'       =>  '1',
            'email'			    =>  'email3@email_falso.com',
            'user_telefono'     =>  '9768541203',
            'password' 		    => bcrypt('123456'),
            'rol_id'            => '6',
            'empresa_id'        => '2',
        ]);

        User::create([
            'user_nombre'		=>	'Omar',
            'user_apellido'		=>	'López',
            'user_rut'          =>  '69999999-9',
            'user_cargo'        =>  'Operador',
            'user_estado'       =>  '1',
            'email'			    =>  'email4@email_falso.com',
            'user_telefono'     =>  '8731235689',
            'password' 		    => bcrypt('123456'),
            'rol_id'            => '4',
            'empresa_id'        => '3',
        ]);

        User::create([
            'user_nombre'		=>	'Leonardo',
            'user_apellido'		=>	'Moreno',
            'user_rut'          =>  '90000000-0',
            'user_cargo'        =>  'Conductor',
            'user_estado'       =>  '1',
            'email'			    =>  'email5@email_falso.com',
            'user_telefono'     =>  '977763391',
            'password' 		    => bcrypt('123456'),
            'rol_id'            => '5',
            'empresa_id'        => '3',
        ]);

        User::create([
            'user_nombre'		=>	'Jonathan',
            'user_apellido'		=>	'Merchán',
            'user_rut'          =>  '01111111-1',
            'user_cargo'        =>  'Pickero',
            'user_estado'       =>  '1',
            'email'			    =>  'email6@email_falso.com',
            'user_telefono'     =>  '955563304',
            'password' 		    => bcrypt('123456'),
            'rol_id'            => '6',
            'empresa_id'        => '3',
        ]);

        User::create([
            'user_nombre'		=>	'Enrique',
            'user_apellido'		=>	'Tineo',
            'user_rut'          =>  '12222222-2',
            'user_cargo'        =>  'Operador',
            'user_estado'       =>  '1',
            'email'			    =>  'email7@email_falso.com',
            'user_telefono'     =>  '7245618497',
            'password' 		    => bcrypt('123456'),
            'rol_id'            => '4',
            'empresa_id'        => '2',
        ]);
    }
}
