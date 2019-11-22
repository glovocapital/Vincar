<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tipo_dano extends Model
{
    protected $primaryKey = 'tipo_dano_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tipo_dano_descripcion'
    ];
}
