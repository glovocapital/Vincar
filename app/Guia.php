<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Guia extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'guia_id';
    // protected $table = 'fotos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'guia_ruta', 'guia_fecha', 'guia_destino', 'empresa_id'
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id', 'empresa_id');
    }
}
