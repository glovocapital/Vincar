<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Camion extends Model
{
    protected $primaryKey = 'camion_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'camion_patente', 'camion_modelo', 'camion_marca', 'camion_anio', 'camion_capacidad',
    ];

    public function belongsToEmpresa (){
        return $this->belongsTo(Empresa::class, 'empresa_id', 'empresa_id');
    }
}
