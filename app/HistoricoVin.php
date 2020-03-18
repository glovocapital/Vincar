<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HistoricoVin extends Model
{
    protected $primaryKey = 'historico_vin_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'vin_id', 'vin_estado_inventario_id', 'historico_vin_fecha', 'user_id', 'origen_id', 'destino_id', 'empresa_id', 'historico_vin_descripcion'
    ];

    public function oneResponsable(){
        return $this->hasOne(User::class, 'user_id', 'user_id');
    }
    
    public function oneVin(){
        return $this->hasOne(Vin::class, 'vin_id', 'vin_id');
    }
    
    public function oneVinEstadoInventario(){
        return $this->hasOne(Vin::class, 'vin_estado_inventario_id', 'vin_estado_inventario_id');
    }
    
    public function oneOrigen(){
        return $this->hasOne(Bloque::class, 'bloque_id', 'origen_id');
    }

    
    public function oneDestino(){
        return $this->hasOne(Bloque::class, 'bloque_id', 'destino_id');
    }

    public function oneEmpresa(){
        // $vin = $this->oneVin();

        // $user = $vin->oneUser();

        return $this->oneVin->oneUser->belongsToEmpresa();
    }
}
