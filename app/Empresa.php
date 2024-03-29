<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Empresa extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'empresa_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'empresa_rut', 'empresa_razon_social', 'empresa_giro', 'empresa_direccion', 'empresa_nombre_contacto',
        'empresa_telefono_contacto', 'empresa_es_proveedor'
    ];

    public function onePais (){
        return $this->hasOne(Pais::class, 'pais_id', 'pais_id');
    }

    public function manyUsers (){
        return $this->hasMany(User::class, 'empresa_id');
    }

    public function manyCamiones (){
        return $this->hasMany(Camion::class);
    }

    public function vins()
    {
        $users = $this->manyUsers();

        $vins = [];

        foreach ($users as $user) {
            array_push($vins, $user->vins());
        }

        return $vins;
    }

    public function oneTipoProveedor ($tipo_proveedor_id){
        $tipoProveedor = DB::table('tipo_proveedores')
            ->where('tipo_proveedor_id', $this->tipo_proveedor_id)
            ->first();
        return $tipoProveedor;
    }
}
