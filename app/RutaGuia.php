<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RutaGuia extends Model
{
<<<<<<< HEAD
    protected $primaryKey = 'ruta_guias_id';
=======
    protected $primaryKey = 'ruta_guia_id';
>>>>>>> c97c4c2f85486b3495167a1a30b57f7ca111948e
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
