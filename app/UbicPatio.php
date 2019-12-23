<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UbicPatio extends Model
{
    protected $primaryKey = 'ubic_patio_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ubic_patio_fila', 'ubic_patio_columna', 'ubic_patio_latitud', 'ubic_patio_longitud', 'ubic_patio_ocupada', 'bloque_id'
    ];

    public function marcarOcupada(){
        $this->ubic_patio_ocupada = true;

        $this->save();
    }

    public function oneBloque(){
        return $this->hasOne(Bloque::class, 'bloque_id', 'bloque_id');
    }
    
    public function oneVin(){
        return $this->hasOne(Vin::class, 'vin_id', 'vin_id');
    }
}
