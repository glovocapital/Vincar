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

                $marca = Marca::whereRaw('LOWER(marca_nombre) LIKE LOWER(?)', "%" . trim($row['marca']) . "%")->first();
                                
                $vin = DB::table('vins')
                    ->where('vin_codigo', $row['vin'])
                    ->exists();

                if($vin != true) {
                    if($marca != null){
                        $vin_nuevo = Vin::create([
                            'vin_codigo' => trim($row['vin']),
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
                                $fecha, 
                                $user->user_id,
                                null,
                                null,
                                $user->empresa_id,
                                "Anuncio de llegada del VIN.",
                                "Origen: Puerto",
                                "Patio: Bloque y Ubicación por asignar."
                            ]
                        );
                    } else{
                        flash('Error: Marca no encontrada para el VIN: ' . $row['vin'] . '. Fila: ' . $fila . ' del documento. VIN no agregado, verifique que esté bien escrita la marca.')->error();
                    }
                } else {
                    $vin = Vin::where('vin_codigo', $row['vin'])->first();

                    if ($vin->vin_estado_inventario_id == 7 || $vin->vin_estado_inventario_id == 8){
                        $comentario = "";
                        
                        $vin->vin_estado_inventario_id = 1;

                        if ($vin->user_id != $user->user_id){
                            $vin->user_id = $user->user_id;
                            $comentario = "VIN cambió de propietario.";
                        }

                        $vin->vin_fec_ingreso = $fecha;
                        $vin->vin_predespacho = false;
                        $vin->vin_bloqueado_entrega = false;
                        $vin->vin_fecha_entrega = null;
                        $vin->vin_fecha_agendado = null;
                        
                        $vin->save();

                        DB::insert('INSERT INTO historico_vins
                                (vin_id, vin_estado_inventario_id, historico_vin_fecha, user_id,
                                origen_id, destino_id, empresa_id, historico_vin_descripcion, origen_texto, destino_texto)
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
                                [
                                    $vin->vin_id,
                                    $vin->vin_estado_inventario_id,
                                    $fecha, 
                                    $user->user_id,
                                    null,
                                    null,
                                    $user->empresa_id,
                                    "VIN reingresando al sistema. " . $comentario,
                                    "Origen: Reingreso de VIN al sistema",
                                    "Patio: Bloque y Ubicación por asignar."
                                ]
                            );  
                    } else {
                        flash('Error: El VIN: ' . $vin->vin_codigo . 'no puede ser reingresado porque ya existe en el sistema con estado: ' . $vin->oneVinEstadoInventario())->error();
                    }
                } 
                DB::commit();
            } catch (\Throwable $th) {
                DB::rollBack();
                flash('Error inesperado al insertar datos masivos.')->error();
                return back();
            }
        }
        flash('VINs cargados exitosamente.')->success();
    }
}

