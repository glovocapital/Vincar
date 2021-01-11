<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HistoricoTour extends Model
{
    protected $primaryKey = 'historico_tour_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tour_id', 'ruta_id', 'vin_id', 'cliente_id', 'historico_tour_fecha_inicio', 'historico_tour_fecha_fin', 'proveedor_id', 'historico_tour_condicion_entrega', 'historico_tour_numero_guia_ruta', 'historico_tour_descripcion'
    ];

    public function oneTour(){
        return $this->hasOne(Tour::class, 'tour_id', 'tour_id');
    }

    public function oneRuta(){
        return $this->hasOne(Ruta::class, 'ruta_id', 'ruta_id');
    }

    public function oneVin(){
        return $this->hasOne(Vin::class, 'vin_id', 'vin_id');
    }

    public function oneCliente(){
        return $this->hasOne(Empresa::class, 'empresa_id', 'cliente_id');
    }

    public function oneProveedor(){
        return $this->hasOne(Empresa::class, 'empresa_id', 'proveedor_id');
    }

    public function oneVinEstadoInventario(){
        $estadoInventario = DB::table('vin_estado_inventarios')
            ->where('vin_estado_inventario_id', $this->vin_estado_inventario_id)
            ->first();

        return $estadoInventario->vin_estado_inventario_desc;
    }

    public function oneOrigen(){
        return $this->hasOne(Bloque::class, 'bloque_id', 'origen_id');
    }


    public function oneDestino(){
        return $this->hasOne(Bloque::class, 'bloque_id', 'destino_id');
    }

    public function oneEmpresa(){
        return $this->oneVin->oneUser->belongsToEmpresa();
    }
}
