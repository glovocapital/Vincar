<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FotoNN extends Model
{
    protected $primaryKey = 'foto_id';
    protected $table = 'pre_inspeccion_vin_fotos';

    protected $fillable = [
        'foto_fecha', 'foto_descripcion', 'foto_ubic_archivo', 'foto_coord_lon', 'foto_coord_lat'
    ];

    public function vehiculo()
    {
        return $this->belongsTo(VehiculoNN::class, 'vin_codigo', 'vin_codigo');
    }
}
