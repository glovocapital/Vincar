<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Foto extends Model
{
    protected $primaryKey = 'foto_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'foto_fecha', 'foto_descripcion', 'foto_ubic_archivo', 'foto_coord_lon', 'foto_coord_lat'
    ];

    public function belongsToDanoPieza(){
        return $this->belongsTo(DanoPieza::class, 'dano_pieza_id', 'dano_pieza_id');
    }
}
