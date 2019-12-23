<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bloque extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'bloque_id';
    protected $table = 'bloques';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'bloque_nombre', 'bloque_filas', 'bloque_columnas', 'bloque_coord_lat', 'bloque_coord_lon', 'patio_id'
    ];
    
    public function manyUbicPatios(){
        return $this->hasMany(UbicPatio::class);
    }

    public function onePatio(){
        return $this->hasOne(Patio::class, 'patio_id', 'patio_id');
    }
}
