<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Conductor extends Model
{
    use SoftDeletes;
    protected $table = 'conductors';
    protected $primaryKey = 'conductor_id';

    public function oneLicencia()
    {
        return $this->hasOne('App\TipoLicencia', 'tipo_licencia_id', 'tipo_licencia_id');
    }

    public function belongstoUser()
    {
        return $this->belongsTo('App\User', 'user_id', 'user_id');

    }




}
