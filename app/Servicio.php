<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Servicio extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'servicios_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'caracteristica_vin_id'
    ];

    public function oneDivisa (){
        return $this->hasOne(Divisa::class, 'divisa_id', 'divisa_id');
    }

    public function oneMarca (){
        return $this->hasOne(Marca::class, 'marca_id', 'marca_id');
    }

    public function oneValorA (){
        return $this->hasOne(ValoresAsociado::class, 'valor_asociado_id', 'valor_asociado_id');
    }

    public function oneProducto (){
        return $this->hasOne(Producto::class, 'producto_id', 'producto_id');
    }

    public function oneEmpresa(){
        return $this->hasOne(Empresa::class, 'empresa_id', 'cliente_id');

    }

}
