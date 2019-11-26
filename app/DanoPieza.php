<?php

namespace App;
;
use Illuminate\Database\Eloquent\Model;

class DanoPieza extends Model
{
    protected $primaryKey = 'dano_pieza_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'dano_pieza_observaciones', 'dano_pieza_dano'
    ];

    public function belongsToInspeccion(){
        return $this->belongsTo(Inspeccion::class, 'inspeccion_id', 'inspeccion_id');
    }

    public function onePieza(){
        return $this->hasOne(Pieza::class, 'pieza_id', 'pieza_id');
    }

    public function oneTipoDano(){
        return $this->hasOne(TipoDano::class, 'tipo_dano_id', 'tipo_dano_id');
    }

    public function oneGravedad()
    {
        $gravedad = DB::table('gravedades')
        ->where('gravedad_id', $this->gravedad_id)
        ->first();
        return $gravedad;
    }
    
    public function manyFotos(){
        return $this->hasMany(Foto::class, 'dano_pieza_id');
    }
}
