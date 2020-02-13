<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Guia_Vin extends Model
{
    protected $primaryKey = 'guia_vin_id';
    protected $table = 'guias_vins';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'guia_id','vin_id'
    ];
}
