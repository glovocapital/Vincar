<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CaracteristicaVin extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'caracteristica_vin_id';
}
