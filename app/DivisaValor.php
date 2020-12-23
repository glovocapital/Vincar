<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DivisaValor extends Model
{
    protected $primaryKey = 'divisa_valor_id';
    protected $table = 'divisas_valor';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'divisa_id', 'divisa_valor_fecha', 'divisa_valor_valor'
    ];

}
