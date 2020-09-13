<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FotoNN extends Model
{
    protected $primaryKey = 'foto_id';
    protected $table = 'pre_inspeccion_vin_fotos';

    public function vehiculo()
    {
        return $this->belongsTo(VehiculoNN::class, 'vin_codigo', 'vin_codigo');
    }
}
