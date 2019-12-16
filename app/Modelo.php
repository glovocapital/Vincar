<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Modelo extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'modelo_id';


   public function BelongsMarca()
       {
           return $this->belongsTo('App\Marca','marca_id','marca_id');
       }






}



