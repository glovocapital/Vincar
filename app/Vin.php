<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Database\Eloquent\Collection;

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
        'vin_codigo', 'vin_patente', 'vin_modelo', 'vin_marca', 'vin_color', 'vin_motor', 'vin_segmento',
        'vin_fec_ingreso', 'user_id', 'vin_estado_inventario_id', 'vin_sub_estado_inventario_id'
    ];

    public function oneUser(){
        return $this->hasOne(User::class, 'user_id', 'user_id');
    }

    public function oneMarca(){
        return $this->hasOne(Marca::class, 'marca_id', 'vin_marca');
    }

    public function oneVinEstadoInventario()
    {
        $estadoInventario = DB::table('vin_estado_inventarios')
            ->where('vin_estado_inventario_id', $this->vin_estado_inventario_id)
            ->first();

        return $estadoInventario->vin_estado_inventario_desc;
    }

    public function estadoInventario()
    {
        return $this->hasOne(EstadoInventario::class, 'vin_estado_inventario_id', 'vin_estado_inventario_id');
    }

    public function oneVinSubEstadoInventario()
    {
        $subEstadoInventario = DB::table('vin_sub_estado_inventarios')
            ->where('vin_sub_estado_inventario_id', $this->vin_sub_estado_inventario_id)
            ->first();

        return $subEstadoInventario->vin_sub_estado_inventario_desc;
    }

    public function guiaVins(){
        return $this->hasMany(GuiaVin::class, 'vin_id', 'vin_id');
    }

    public function guias()
    {
        $arrayGuias = [];

        foreach ($this->guiaVins as $guiaVin){
            array_push($arrayGuias, $guiaVin->guia);
        }

        $guias = new Collection($arrayGuias);

        return $guias;
    }

    public function ubicPatio(){
        return $this->hasOne(UbicPatio::class, 'vin_id', 'vin_id');
    }

    public function empresa()
    {
        return $this->oneUser->belongsToEmpresa;
    }

    public function entregas()
    {
        return $this->hasMany(Entrega::class, 'vin_id', 'vin_id')->orderBy('created_at', 'DESC');
    }

    public function ultimaEntrega()
    {
        $entrega = new Collection($this->entregas->last());
        return $entrega;
    }
}
