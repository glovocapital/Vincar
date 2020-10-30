<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Predespacho extends Model
{
    use SoftDeletes;
    
    protected $primaryKey = 'predespacho_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tipo_agendamiento_id', 'predespacho_origen', 'predespacho_destino',
    ];

    public function responsable(){
        return $this->hasOne(User::class, 'user_id', 'responsable_id');
    }

    public function user(){
        return $this->hasOne(User::class, 'user_id', 'user_id');
    }
    
    public function vin(){
        return $this->hasOne(Vin::class, 'vin_id', 'vin_id');
    }
    
    public function nombreResponsable(){
        return $this->responsable->user_nombre.' '.$this->responsable->user_apellido;
    }

    public function nombreUsuario(){
        return $this->user->user_nombre.' '.$this->user->user_apellido;
    }

    public function codigoVin(){
        return $this->vin->vin_codigo;
    }
}
