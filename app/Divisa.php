<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Divisa extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'divisa_id';
    //
}
