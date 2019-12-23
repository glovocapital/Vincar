<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Vin;
use GuzzleHttp\Psr7\Request;
use Maatwebsite\Excel\Concerns\Importable;


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
            Vin::create([
                'vin_codigo' => $row['vin'],
                'vin_patente' => $row['patente'],
                'vin_marca' => $row['marca'],
                'vin_modelo' => $row['modelo'],
                'vin_color' => $row['color'],
                'vin_motor' => $row['motor'],
                'vin_segmento' => $row['segmento'],
                'vin_fec_ingreso' => $row['fechadeingreso'],
                'vin_estado_inventario_id' => $row['estadodeinventario'],
                'vin_sub_estado_inventario_id' => $row['subestadodeinventario'],
                'user_id' => $row['usuario'],
            ]);
        }
    }
}
