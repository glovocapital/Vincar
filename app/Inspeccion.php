<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inspeccion extends Model
{
    protected $primaryKey = 'inspeccion_id';
    protected $table = 'inspecciones';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'inspeccion_fecha', 'inspeccion_dano'
    ];

    public function marcarDano(){
        $this->inspeccion_dano = true;

        $this->save();
    }

    public function oneResponsable(){
        return $this->hasOne(User::class, 'user_id', 'responsable_id');
    }

    public function oneCliente(){
        return $this->hasOne(User::class, 'user_id', 'cliente_id');
    }

    public function oneVin()
    {
        return $this->hasOne(Vin::class, 'vin_id', 'vin_id');
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
