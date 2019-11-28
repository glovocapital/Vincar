<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pais extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'pais_id';
    protected $table = 'paises';

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
