<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VehiculoNN extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'vin_id';
    protected $table = 'nn_vehiculos';

    public function user(){
        return $this->hasOne(User::class, 'user_id', 'user_id'); 
    }

    public function oneMarca(){
        return $this->hasOne(Marca::class, 'marca_id', 'vin_marca');
    }

    public function fotos(){
        return $this->hasMany(FotoNN::class, 'vin_codigo', 'vin_codigo'); 
    }
}
