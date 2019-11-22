<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pieza extends Model
{
    protected $primaryKey = 'pieza_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'pieza_descripcion'
    ];

    public function oneSubArea(){
        $subArea = DB::table('pieza_sub_areas')
            ->where('pieza_sub_area_id', $this->pieza_sub_area_id)
            ->first();

        return $subArea;
    }
}
