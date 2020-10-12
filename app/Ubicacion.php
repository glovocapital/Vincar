<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ubicacion extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'ubicacion_id';
    protected $table = 'ubicaciones';
    
    public function belongsToRuta()
    {
        return $this->belongsTo(Ruta::class, 'ruta_id', 'ruta_id');
    }
}
