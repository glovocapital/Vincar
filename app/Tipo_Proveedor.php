<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tipo_Proveedor extends Model
{
    use SoftDeletes;
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
