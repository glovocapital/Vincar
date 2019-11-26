<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pais extends Model
{
    protected $primaryKey = 'pais_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pais_nombre'
    ];

    public function manyEmpresas(){
        return $this->hasMany(Empresa::class);
    }
}
