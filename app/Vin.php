<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Vin extends Model
{
    protected $primaryKey = 'vin_id';
    protected $guarded = [];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'vin_codigo', 'vin_patente', 'vin_modelo', 'vin_marca', 'vin_color', 'vin_motor', 'vin_segmento', 'vin_fec_ingreso', 'user_id','vin_estado_inventario', 'vin_sub_estado_inventario'
    ];

    public function oneUser(){
        return $this->hasOne(User::class, 'user_id', 'user_id');
    }

    public function oneVinEstadoInventario()
    {
        $estadoInventario = DB::table('vin_estado_inventarios')
            ->where('vin_estado_inventario_id', $this->vin_estado_inventario_id)
            ->first();

        return $estadoInventario->vin_estado_inventario_desc;
    }

    public function oneVinSubEstadoInventario()
    {
        $subEstadoInventario = DB::table('vin_sub_estado_inventarios')
            ->where('vin_sub_estado_inventario_id', $this->vin_sub_estado_inventario_id)
            ->first();

        return $subEstadoInventario->vin_sub_estado_inventario_desc;
    }
}
