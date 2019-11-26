<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Remolque extends Model
{
    protected $primaryKey = 'remolque_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'remolque_patente', 'remolque_modelo', 'remolque_marca', 'remolque_anio', 'remolque_capacidad',
    ];

    public function belongsToEmpresa (){
        return $this->belongsTo(Empresa::class, 'empresa_id', 'empresa_id');
    }
}
