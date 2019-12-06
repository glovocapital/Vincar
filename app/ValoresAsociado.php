<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ValoresAsociado extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'valor_asociado_id';

    //
}
