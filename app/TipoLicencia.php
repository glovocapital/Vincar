<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoLicencia extends Model
{
    protected $primaryKey = 'tipo_licencia_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tipo_licencia_nombre'
    ];
}
