<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ViajesFalso extends Model
{
    protected $primaryKey = 'viaje_falso_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    /* protected $fillable = [
        'tour_guia', 'tour_fec_inicio', 'tour_fec_fin', 'tour_finalizado'
    ]; */
    
    public function belongsToEmpresa (){
        return $this->belongsTo(Empresa::class, 'empresa_id', 'empresa_id');
    }
}
