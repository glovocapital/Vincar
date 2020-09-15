<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Camion extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'camion_id';
    protected $table = 'camiones';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'camion_patente', 'camion_modelo', 'camion_marca', 'camion_anio', 'camion_capacidad',
    ];

    public function oneMarca(){
        return $this->hasOne(Marca::class, 'marca_id', 'camion_marca');
    }

    public function belongsToEmpresa (){
        return $this->belongsTo(Empresa::class, 'empresa_id', 'empresa_id');
    }
}
