<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Entrega extends Model
{
    protected $primaryKey = 'entrega_id';
    protected $table = 'entregas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'entrega_fecha', 'inspeccion_dano', 'tipo_id','recibe_rut','recibe_nombre','recibe_apellido','foto_rut','foto_patente'
    ];
    
    public function oneResponsable(){
        return $this->hasOne(User::class, 'user_id', 'responsable_id');
    }

    public function oneVin()
    {
        return $this->hasOne(Vin::class, 'vin_id', 'vin_id');
    }

}
