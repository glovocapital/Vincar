<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Licencia extends Model
{
    protected $primaryKey = 'licencia_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'licencia_nro_documento', 'licencia_fecha_expedicion', 'licencia_fecha_vencimiento'
    ];
    
    public function oneTipoLicencia (){
        return $this->hasOne(TipoLicencia::class, 'tipo_licencia_id');
    }

    public function belongsToUser (){
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
