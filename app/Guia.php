<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Guia extends Model
{
    protected $primaryKey = 'guia_id';
    // protected $table = 'fotos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'guia_ruta', 'guia_fecha', 'guia_destino', 'empresa_id'
    ];
}
