<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Patio extends Model
{
    protected $primaryKey = 'patio_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'patio_nombre', 'patio_direccion', 'patio_bloques', 'patio_coord_lat', 'patio_coord_lon'
    ];
    
    public function manyUbicPatios(){
        return $this->hasMany(UbicPatio::class);
    }
}
