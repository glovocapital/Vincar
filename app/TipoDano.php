<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoDano extends Model
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
