<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Marca extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'marca_id';

    protected $fillable = [
        'marca_nombre'
    ];



    public function hasModelos()
    {
        return $this->HasMany('App\Modelo', 'modelo_id','modelo_id');
    }




}
