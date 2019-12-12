<?php

namespace App;

use DB;
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
        'dano_pieza_observaciones', 'inspeccion_id', 'pieza_id', 'tipo_dano_id', 'gravedad_id', 'pieza_sub_area_id'
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
        return $gravedad->gravedad_descripcion;
    }

    public function oneSubArea()
    {
        $subArea = DB::table('pieza_sub_areas')
        ->where('pieza_sub_area_id', $this->pieza_sub_area_id)
        ->first();
        return $subArea->pieza_sub_area_desc;
    }

    public function oneFoto(){
        return $this->hasOne(Foto::class, 'dano_pieza_id', 'dano_pieza_id');
    }
    
    public function manyFotos(){
        return $this->hasMany(Foto::class, 'dano_pieza_id');
    }
}
