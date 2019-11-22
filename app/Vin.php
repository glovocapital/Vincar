<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use User;
use VinEstadoInventario;
use VinSubEstadoInventario;

class Vin extends Model
{
    protected $primaryKey = 'vin_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'vin_patente', 'vin_modelo', 'vin_marca', 'vin_color', 'vin_motor', 'vin_segmento', 'vin_fec_ingreso'
    ];

    public function hasUser($user_id){
        $user = User::find($user_id);

        return $user;
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
