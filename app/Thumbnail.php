<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Thumbnail extends Model
{
    protected $primaryKey = 'thumbnail_id';

    protected $table = 'thumbnails';
    protected $fillable = [
        'thumbnail_nombre', 'thumbnail_imagen'
    ];


}
