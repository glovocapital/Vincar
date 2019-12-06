<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'producto_id';

    protected $fillable = [
        'producto_codigo'
    ];
}
