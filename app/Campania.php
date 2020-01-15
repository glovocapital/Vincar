<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Campania extends Model
{
    use SoftDeletes;
    
    protected $primaryKey = 'campania_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'campania_fecha_finalizacion', 'campania_observaciones', 'vin_id', 'user_id'
    ];

    public function oneUser(){
        return $this->hasOne(User::class, 'user_id', 'user_id');
    }

    
    public function oneVin(){
        return $this->hasOne(Vin::class, 'vin_id', 'vin_id');
    }
    
    public function nombreUsuario(){
        return $this->oneUser->user_nombre.' '.$this->oneUser->user_apellido;
    }

    public function codigoVin(){
        return $this->oneVin->vin_codigo;
    }
}
