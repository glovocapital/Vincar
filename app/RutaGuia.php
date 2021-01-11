<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RutaGuia extends Model
{
    protected $primaryKey = 'ruta_guia_id';
    protected $table = 'ruta_guias';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ruta_id', 'guia_id'
    ];

    public function oneRuta(){
        return $this->belongsTo(Ruta::class, 'ruta_id', 'ruta_id');
    }

    public function oneGuia(){
        return $this->belongsTo(Guia::class, 'guia_id', 'guia_id');
    }
}
