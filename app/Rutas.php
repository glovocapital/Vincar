<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rutas extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'ruta_id';

    protected $fillable = [

    ];







}
