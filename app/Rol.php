<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    //use SoftDeletes;

    protected $primaryKey = 'rol_id';
    protected $guarded = ['rol_id'];
    protected $rol = ['deleted_at'];
    protected $table = 'roles';
}
