<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GuiaVin extends Model
{
    protected $primaryKey = 'guia_vin_id';
    protected $table = 'guia_vins';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'guia_id','vin_id'
    ];

    public function vin()
    {
        return $this->hasOne(Vin::class, 'vin_id', 'vin_id');
    }

    public function guia()
    {
        return $this->hasOne(Guia::class, 'guia_id', 'guia_id');
    }
}
