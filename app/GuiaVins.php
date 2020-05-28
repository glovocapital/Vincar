<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GuiaVins extends Model
{
    protected $primaryKey = 'guia_vin_id';
    protected $table = 'guia_vins';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'guia_id','vin_id'
    ];
}
