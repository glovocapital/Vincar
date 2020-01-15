<?php

namespace App\Imports;

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


        foreach ($rows as $row)
        {

            $fecha = date("Y-m-d",$row['fecha_de_ingreso']);



            $vin = DB::table('vins')
                ->where('vin_codigo', $row['vin'])
                ->exists();

            $validate = DB::table('vin_estado_inventarios')
                ->where('vin_estado_inventario_desc', $row['estado_de_inventario'])
                ->exists();

                if($vin != true) {
                    if($validate == true){
                        if( trim($row['estado_de_inventario']) == 'Anunciado' ||
                            trim($row['estado_de_inventario']) == 'anunciado' ||
                            trim($row['estado_de_inventario']) == 'ANUNCIADO')
                        {
                            Vin::create([
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
                                'user_id' =>  Auth::id(),
                            ]);
                        } elseif( trim($row['estado_de_inventario']) == 'Arribado' ||
                                  trim($row['estado_de_inventario']) == 'arribado' ||
                                  trim($row['estado_de_inventario']) == 'ARRIBADO')
                        {
                            Vin::create([
                                'vin_codigo' => $row['vin'],
                                'vin_patente' => $row['patente'],
                                'vin_marca' => $row['marca'],
                                'vin_modelo' => $row['modelo'],
                                'vin_color' => $row['color'],
                                'vin_motor' => $row['motor'],
                                'vin_segmento' => $row['segmento'],
                                'vin_fec_ingreso' => $fecha,
                                'vin_estado_inventario_id' => 2,
                                'vin_sub_estado_inventario_id' => null,
                                'user_id' => Auth::id(),
                            ]);
                        } elseif( trim($row['estado_de_inventario']) == 'Tránsito' ||
                                  trim($row['estado_de_inventario']) == 'Transito' ||
                                  trim($row['estado_de_inventario']) == 'transito' ||
                                  trim($row['estado_de_inventario']) == 'tránsito' ||
                                  trim($row['estado_de_inventario']) == 'TRÁNSITO' ||
                                  trim($row['estado_de_inventario']) == 'TRANSITO') {
                            Vin::create([
                                'vin_codigo' => $row['vin'],
                                'vin_patente' => $row['patente'],
                                'vin_marca' => $row['marca'],
                                'vin_modelo' => $row['modelo'],
                                'vin_color' => $row['color'],
                                'vin_motor' => $row['motor'],
                                'vin_segmento' => $row['segmento'],
                                'vin_fec_ingreso' => $fecha,
                                'vin_estado_inventario_id' => 3,
                                'vin_sub_estado_inventario_id' => null,
                                'user_id' =>  Auth::id(),
                            ]);
                        } elseif( trim($row['estado_de_inventario']) == 'Disponible para la venta' ||
                                  trim($row['estado_de_inventario']) == 'Disponible Para La Venta' ||
                                  trim($row['estado_de_inventario']) == 'DISPONIBLE PARA LA VENTA' ||
                                  trim($row['estado_de_inventario']) == 'disponible para la venta' ) {
                            Vin::create([
                                'vin_codigo' => $row['vin'],
                                'vin_patente' => $row['patente'],
                                'vin_marca' => $row['marca'],
                                'vin_modelo' => $row['modelo'],
                                'vin_color' => $row['color'],
                                'vin_motor' => $row['motor'],
                                'vin_segmento' => $row['segmento'],
                                'vin_fec_ingreso' => $fecha,
                                'vin_estado_inventario_id' => 4,
                                'vin_sub_estado_inventario_id' =>null,
                                'user_id' =>  Auth::id(),
                            ]);
                        } elseif( trim($row['estado_de_inventario']) == 'No disponible para la venta' ||
                        trim($row['estado_de_inventario']) == ' No Disponible Para La Venta' ||
                        trim($row['estado_de_inventario']) == 'NO DISPONIBLE PARA LA VENTA' ||
                        trim($row['estado_de_inventario']) == 'no disponible para la venta' ) {
                            Vin::create([
                                'vin_codigo' => $row['vin'],
                                'vin_patente' => $row['patente'],
                                'vin_marca' => $row['marca'],
                                'vin_modelo' => $row['modelo'],
                                'vin_color' => $row['color'],
                                'vin_motor' => $row['motor'],
                                'vin_segmento' => $row['segmento'],
                                'vin_fec_ingreso' => $fecha,
                                'vin_estado_inventario_id' => 5,
                                'vin_sub_estado_inventario_id' => null,
                                'user_id' =>  Auth::id(),
                            ]);
                        } elseif( trim($row['estado_de_inventario']) == 'Suprimido' ||
                                  trim($row['estado_de_inventario']) == 'SUPRIMIDO' ||
                                  trim($row['estado_de_inventario']) == 'suprimido') {
                            Vin::create([
                                'vin_codigo' => $row['vin'],
                                'vin_patente' => $row['patente'],
                                'vin_marca' => $row['marca'],
                                'vin_modelo' => $row['modelo'],
                                'vin_color' => $row['color'],
                                'vin_motor' => $row['motor'],
                                'vin_segmento' => $row['segmento'],
                                'vin_fec_ingreso' => $fecha,
                                'vin_estado_inventario_id' => 6,
                                'vin_sub_estado_inventario_id' => null,
                                'user_id' =>  Auth::id(),
                            ]);
                        } elseif( trim($row['estado_de_inventario']) == 'Entregado' ||
                                  trim($row['estado_de_inventario']) == 'entregado' ||
                                  trim($row['estado_de_inventario']) == 'ENTREGADO') {
                            Vin::create([
                                'vin_codigo' => $row['vin'],
                                'vin_patente' => $row['patente'],
                                'vin_marca' => $row['marca'],
                                'vin_modelo' => $row['modelo'],
                                'vin_color' => $row['color'],
                                'vin_motor' => $row['motor'],
                                'vin_segmento' => $row['segmento'],
                                'vin_fec_ingreso' => $fecha,
                                'vin_estado_inventario_id' => 7,
                                'vin_sub_estado_inventario_id' => null,
                                'user_id' =>  Auth::id(),
                            ]);
                        }
                    }

                }


        }
    }
}
