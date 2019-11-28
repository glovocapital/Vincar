<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Destino extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'destino_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'destino_nombre'
    ];
}
