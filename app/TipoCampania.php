<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoCampania extends Model
{
    protected $primaryKey = 'tipo_campania_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tipo_campania_descripcion'
    ];
}
