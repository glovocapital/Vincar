<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tipo_Proveedor extends Model
{
    protected $primaryKey = 'tipo_proveedor_id';
    protected $table = 'tipo_proveedores';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tipo_proveedor_desc'
    ];
}
