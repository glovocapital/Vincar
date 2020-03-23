<?php

namespace App\Imports;

use App\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Vin;
use GuzzleHttp\Psr7\Request;
use Maatwebsite\Excel\Concerns\Importable;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class VinsCollectionImport implements ToCollection, WithHeadingRow
{
    use Importable;
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        //$fecha = Carbon::now();
        $fecha = date('Y-m-d');

        $user = User::find(Auth::id());

        foreach ($rows as $row)
        {
            try {
                DB::beginTransaction();
    
                $vin = DB::table('vins')
                    ->where('vin_codigo', $row['vin'])
                    ->exists();

                if($vin != true) {
                    $vin_nuevo = Vin::create([
                        'vin_codigo' => $row['vin'],
                        'vin_patente' => $row['patente'],
                        'vin_marca' => $row['marca'],
                        'vin_modelo' => $row['modelo'],
                        'vin_color' => $row['color'],
                        'vin_motor' => $row['motor'],
                        'vin_segmento' => $row['segmento'],
                        'vin_fec_ingreso' => $fecha,
                        'vin_estado_inventario_id' => 1,
                        'vin_sub_estado_inventario_id' => null,
                        'user_id' =>  $user->user_id,
                    ]);

                    DB::insert('INSERT INTO historico_vins 
                            (vin_id, vin_estado_inventario_id, historico_vin_fecha, user_id, 
                            origen_id, destino_id, empresa_id, historico_vin_descripcion) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)', 
                            [$vin_nuevo->vin_id, 1, $fecha, $user->user_id, null, null, $user->belongsToEmpresa->empresa_id, "Anuncio de llegada del VIN."]);
                }
                DB::commit();
            } catch (\Throwable $th) {
                DB::rollBack();
                return back()->with('error-msg', 'Error inesperado al insertar datos masivos.');
            }
        }
    }
}

