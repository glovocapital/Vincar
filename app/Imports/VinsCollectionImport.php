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
            $vin = DB::table('vins')
            ->where('vin_codigo', $row['vin'])
            ->exists();

            dd($vin);


            $validate = DB::table('vin_estado_inventarios')
                ->where('vin_estado_inventario_desc', $row['estadodeinventario'])
                ->exists();

                if($vin != true) {
                    if($validate == true){
                        if( trim($row['estadodeinventario']) == 'Anunciado' ||
                            trim($row['estadodeinventario']) == 'anunciado' ||
                            trim($row['estadodeinventario']) == 'ANUNCIADO')
                        {
                            Vin::create([
                                'vin_codigo' => $row['vin'],
                                'vin_patente' => $row['patente'],
                                'vin_marca' => $row['marca'],
                                'vin_modelo' => $row['modelo'],
                                'vin_color' => $row['color'],
                                'vin_motor' => $row['motor'],
                                'vin_segmento' => $row['segmento'],
                                'vin_fec_ingreso' => $row['fechadeingreso'],
                                'vin_estado_inventario_id' => 1,
                                'vin_sub_estado_inventario_id' => $row['subestadodeinventario'],
                                'user_id' => Auth::id(),
                            ]);
                        } elseif( trim($row['estadodeinventario']) == 'Arribado' ||
                                  trim($row['estadodeinventario']) == 'arribado' ||
                                  trim($row['estadodeinventario']) == 'ARRIBADO')
                        {
                            Vin::create([
                                'vin_codigo' => $row['vin'],
                                'vin_patente' => $row['patente'],
                                'vin_marca' => $row['marca'],
                                'vin_modelo' => $row['modelo'],
                                'vin_color' => $row['color'],
                                'vin_motor' => $row['motor'],
                                'vin_segmento' => $row['segmento'],
                                'vin_fec_ingreso' => $row['fechadeingreso'],
                                'vin_estado_inventario_id' => 2,
                                'vin_sub_estado_inventario_id' => $row['subestadodeinventario'],
                                'user_id' => Auth::id(),
                            ]);
                        } elseif( trim($row['estadodeinventario']) == 'Tránsito' ||
                                  trim($row['estadodeinventario']) == 'Transito' ||
                                  trim($row['estadodeinventario']) == 'transito' ||
                                  trim($row['estadodeinventario']) == 'tránsito' ||
                                  trim($row['estadodeinventario']) == 'TRÁNSITO' ||
                                  trim($row['estadodeinventario']) == 'TRANSITO') {
                            Vin::create([
                                'vin_codigo' => $row['vin'],
                                'vin_patente' => $row['patente'],
                                'vin_marca' => $row['marca'],
                                'vin_modelo' => $row['modelo'],
                                'vin_color' => $row['color'],
                                'vin_motor' => $row['motor'],
                                'vin_segmento' => $row['segmento'],
                                'vin_fec_ingreso' => $row['fechadeingreso'],
                                'vin_estado_inventario_id' => 3,
                                'vin_sub_estado_inventario_id' => $row['subestadodeinventario'],
                                'user_id' => Auth::id(),
                            ]);
                        } elseif( trim($row['estadodeinventario']) == 'Disponible para la venta' ||
                                  trim($row['estadodeinventario']) == 'Disponible Para La Venta' ||
                                  trim($row['estadodeinventario']) == 'DISPONIBLE PARA LA VENTA' ||
                                  trim($row['estadodeinventario']) == 'disponible para la venta' ) {
                            Vin::create([
                                'vin_codigo' => $row['vin'],
                                'vin_patente' => $row['patente'],
                                'vin_marca' => $row['marca'],
                                'vin_modelo' => $row['modelo'],
                                'vin_color' => $row['color'],
                                'vin_motor' => $row['motor'],
                                'vin_segmento' => $row['segmento'],
                                'vin_fec_ingreso' => $row['fechadeingreso'],
                                'vin_estado_inventario_id' => 4,
                                'vin_sub_estado_inventario_id' => $row['subestadodeinventario'],
                                'user_id' => Auth::id(),
                            ]);
                        } elseif( trim($row['estadodeinventario']) == 'No disponible para la venta' ||
                        trim($row['estadodeinventario']) == ' No Disponible Para La Venta' ||
                        trim($row['estadodeinventario']) == 'NO DISPONIBLE PARA LA VENTA' ||
                        trim($row['estadodeinventario']) == 'no disponible para la venta' ) {
                            Vin::create([
                                'vin_codigo' => $row['vin'],
                                'vin_patente' => $row['patente'],
                                'vin_marca' => $row['marca'],
                                'vin_modelo' => $row['modelo'],
                                'vin_color' => $row['color'],
                                'vin_motor' => $row['motor'],
                                'vin_segmento' => $row['segmento'],
                                'vin_fec_ingreso' => $row['fechadeingreso'],
                                'vin_estado_inventario_id' => 5,
                                'vin_sub_estado_inventario_id' => $row['subestadodeinventario'],
                                'user_id' => Auth::id(),
                            ]);
                        } elseif( trim($row['estadodeinventario']) == 'Suprimido' ||
                                  trim($row['estadodeinventario']) == 'SUPRIMIDO' ||
                                  trim($row['estadodeinventario']) == 'suprimido') {
                            Vin::create([
                                'vin_codigo' => $row['vin'],
                                'vin_patente' => $row['patente'],
                                'vin_marca' => $row['marca'],
                                'vin_modelo' => $row['modelo'],
                                'vin_color' => $row['color'],
                                'vin_motor' => $row['motor'],
                                'vin_segmento' => $row['segmento'],
                                'vin_fec_ingreso' => $row['fechadeingreso'],
                                'vin_estado_inventario_id' => 6,
                                'vin_sub_estado_inventario_id' => $row['subestadodeinventario'],
                                'user_id' => Auth::id(),
                            ]);
                        } elseif( trim($row['estadodeinventario']) == 'Entregado' ||
                                  trim($row['estadodeinventario']) == 'entregado' ||
                                  trim($row['estadodeinventario']) == 'ENTREGADO') {
                            Vin::create([
                                'vin_codigo' => $row['vin'],
                                'vin_patente' => $row['patente'],
                                'vin_marca' => $row['marca'],
                                'vin_modelo' => $row['modelo'],
                                'vin_color' => $row['color'],
                                'vin_motor' => $row['motor'],
                                'vin_segmento' => $row['segmento'],
                                'vin_fec_ingreso' => $row['fechadeingreso'],
                                'vin_estado_inventario_id' => 7,
                                'vin_sub_estado_inventario_id' => $row['subestadodeinventario'],
                                'user_id' => Auth::id(),
                            ]);
                        }
                    }

                }


        }
    }
}
