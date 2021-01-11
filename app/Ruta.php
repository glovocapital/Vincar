<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ruta extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'ruta_id';

    protected $fillable = [
        'ruta_origen', 'ruta_destino',
    ];

    // protected $fillable = ['code', 'from', 'to', 'load_pc', 'load_lbs', 'dims', 'at_origin', 'at_origin_date', 'current_location', 'current_location_date', 'delivered', 'status', 'pod'];

    protected $dates = ['created_at', 'updated_at'];

    public function rutaGuia(){
        return $this->hasOne(RutaGuia::class, 'ruta_id', 'ruta_id');
    }

    /**
     * Check, is now user must update track (user must update track every two hours).
     *
     * @return bool
     */
    public function getActualizacionVencidaAttribute()
    {
        $date = $this->updated_at->addHour(2);
        $now = Carbon::now();

        return $date->lessThan($now);
    }

    // Una ruta tiene muchas ubicaciones (locations)
    public function ubicaciones()
    {
        return $this->hasMany(Ubicacion::class, 'ubicacion_id', 'ubicacion_id');
    }

    // // Por verse
    // public function photos()
    // {
    //     return $this->hasMany('App\Photo');
    // }

    // Origen de la ruta
    public function getRutaDesdeAttribute()
    {
        if (!empty($this->ruta_ubicacion_actual)) {
            $desde = $this->ruta_ubicacion_actual;
        } elseif (!empty($this->ruta_en_origen)) {
            $desde = $this->ruta_en_origen;
        } elseif (!empty($this->ruta_origen)) {
            $desde = $this->ruta_origen;
        }

        return $desde;
    }

    // // Por verse
    // public function insertPhotos($request)
    // {
    //     if ($request->hasFile('photos')) {
    //         $allowedFileExtension = ['jpeg', 'jpg', 'png'];
    //         $files = $request->file('photos');
    //         foreach ($files as $file) {
    //             $extension = $file->getClientOriginalExtension();

    //             $check = in_array($extension, $allowedFileExtension);

    //             if ($check) {
    //                 $filename = $file->store('public/photos');

    //                 $this->photos()->create([
    //                     'filename' => $filename,
    //                 ]);
    //             }
    //         }
    //     }
    // }

    // /**
    //  * Convert minutes to time.
    //  *
    //  * @param int $time
    //  *
    //  * @return array $value
    //  */
    // public static function convertToHoursMins($time)
    // {
    //     if ($time < 1) {
    //         return;
    //     }
    //     $value['hours'] = floor($time / 60);
    //     $value['days'] = floor($value['hours'] / 24);
    //     $value['minutes'] = ($time % 60);

    //     return $value;
    // }

    // Cálculo de las direcciones con la API de google.
    public static function calcularDirecciones($from, $to)
    {
        $link = 'https://maps.googleapis.com/maps/api/directions/json?origin='.$from.'&destination='.$to.'&key='.config('googlemaps.GOOGLE_MAPS_API_KEY');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, str_replace(' ', '%20', $link));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);

        return json_decode($output);
    }
}
