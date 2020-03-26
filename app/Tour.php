<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    protected $primaryKey = 'tour_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [

    ];

    public function belongsToCamion (){
        return $this->belongsTo(Camion::class, 'camion_id', 'camion_id');
    }

    public function OneConductor (){
        return $this->belongsTo(User::class, 'conductor_id', 'user_id');
    }

    public function belongsToRemolque (){
        return $this->belongsTo(Remolque::class, 'remolque_id', 'remolque_id');
    }

    public function oneCliente (){
        return $this->belongsTo(Empresa::class, 'cliente_id', 'empresa_id');
    }

    public function oneProveedor (){
        return $this->belongsTo(Empresa::class, 'proveedor_id', 'empresa_id');
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
