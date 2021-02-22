<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GuiaVinRuta extends Model
{
    protected $primaryKey = 'guia_vin_ruta_id';
    protected $table = 'guia_vin_rutas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'guia_id','vin_id', 'ruta_id'
    ];
}
