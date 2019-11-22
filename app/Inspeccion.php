<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inspeccion extends Model
{
    protected $primaryKey = 'inspeccion_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'inspeccion_fecha', 'inspeccion_dano'
    ];

    public function tieneDano(){
        $this->inspeccion_dano = true;

        $this->save();
    }

    public function hasResponsable(){
        $responsable = User::find($this->responsable_id);

        return $responsable;
    }

    public function hasCliente(){
        $cliente = User::find($this->cliente_id);

        return $cliente;
    }

    public function oneVin()
    {
        $vin = Vin::find($this->vin_id);
        return $vin;
    }

    public function oneVin()
    {
        $vin = Vin::find($this->vin_id);
        return $vin;
    }

    public function oneVinEstadoInventario()
    {
        $estadoInventario = DB::table('vin_estado_inventarios')
            ->where('vin_estado_inventario_id', $this->vin_estado_inventario_id)
            ->first();
        return $estadoInventario;
    }

    public function oneVinSubEstadoInventario()
    {
        $subEstadoInventario = DB::table('vin_sub_estado_inventarios')
            ->where('vin_sub_estado_inventario_id', $this->vin_sub_estado_inventario_id)
            ->first();
        return $subEstadoInventario;
    }
}
