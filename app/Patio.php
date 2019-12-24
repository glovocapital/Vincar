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
        'patio_nombre', 'patio_bloques', 'patio_coord_lat', 'patio_coord_lon', 'patio_direccion', 
        'region_id', /*'provincia_id',*/ 'comuna_id'
    ];
    
    public function manyBloques(){
        return $this->hasMany(Bloque::class);
    }

    public function oneRegion(){
        $region = DB::table('regiones')
            ->where('region_id', $this->region_id)
            ->first();

        return $region->region_nombre;
    }

    // public function oneProvincia(){
    //     $provincia = DB::table('provincias')
    //     ->where('region_id', $this->region_id)
    //     ->where('provincia_id', $this->provincia_id)
    //     ->first();

    //     return $provincia->provincia_nombre;
    // }

    public function oneComuna(){
        $comuna = DB::table('comunas')
            ->where('region_id', $this->region_id)
            ->where('comuna_id', $this->comuna_id)
            ->first();

        return $comuna->comuna_nombre;
    }
}
