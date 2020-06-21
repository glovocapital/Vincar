<?php

namespace App\Imports;

use App\Marca;
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

        $fila = 0;
        foreach ($rows as $row)
        {
            $fila++;
            try 
            {
                DB::beginTransaction();

                $marca = Marca::where('marca_nombre','like', "%" . trim($row['marca']) . "%")->first();
                
                $vin = DB::table('vins')
                    ->where('vin_codigo', $row['vin'])
                    ->exists();

                if($vin != true) {
                    if($marca != null){
                        $vin_nuevo = Vin::create([
                            'vin_codigo' => $row['vin'],
                            'vin_patente' => $row['patente'],
                            'vin_marca' => $marca->marca_id,
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
                            origen_id, destino_id, empresa_id, historico_vin_descripcion, origen_texto, destino_texto)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
                            [
                                $vin_nuevo->vin_id,
                                1,
                                $fecha, $user->user_id,
                                null,
                                null,
                                $user->belongsToEmpresa->empresa_id,
                                "Anuncio de llegada del VIN.",
                                "Origen: Puerto",
                                "Patio: BLoque y Ubicación por asignar."
                            ]
                        );

                        DB::commit();
                    } else{
                        flash('Error: Marca no encontrada para el VIN: ' . $row['patente'] . '. Fila: ' . $fila . ' del documento. VIN no agregado, verifique que esté bien escrita la marca.')->error();
                    }
                } 
            } catch (\Throwable $th) {
                //dd($th);
                DB::rollBack();
                flash('Error inesperado al insertar datos masivos.')->error();
                return back();
            }
        }
        flash('VINs cargados exitosamente.')->success();
    }
}

