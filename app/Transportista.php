<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transportista extends Model
{
    protected $primaryKey = 'transportista_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'transportista_nombres', 'transportista_apellidos'
    ];
}
