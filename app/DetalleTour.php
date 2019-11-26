<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetalleTour extends Model
{
    protected $primaryKey = 'detalle_tour_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'detalle_tour_descripcion', 'detalle_tour_fecha_expedicion', 'detalle_tour_fecha_vencimiento'
    ];
    
    public function belongsToTour (){
        return $this->belongsTo(Tour::class, 'tour_id', 'tour_id');
    }
    
    public function oneSalida (){
        return $this->hasOne(Destino::class, 'salida_destino_id', 'destino_id');
    }

    public function onellegada (){
        return $this->hasOne(Destino::class, 'llegada_destino_id', 'destino_id');
    }

    public function oneVin (){
        return $this->hasOne(Vin::class, 'vin_id', 'vin_id');
    }
}