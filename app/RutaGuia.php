<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RutaGuia extends Model
{
    protected $primaryKey = 'ruta_guias_id';
    protected $table = 'ruta_guias';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ruta_id', 'guia_id'
    ];
}
