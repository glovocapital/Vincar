<?php

namespace App\Imports;

use App\Vin;
use GuzzleHttp\Psr7\Request;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;

class VinsImport implements ToModel, WithHeadingRow
{
    use Importable;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {


        return new Vin([
            'vin_codigo' => $row['vin'],
            'vin_patente' => $row['patente'],
            'vin_marca' => $row['marca'],
            'vin_modelo' => $row['modelo'],
            'vin_color' => $row['color'],
            'vin_motor' => $row['motor'],
            'vin_segmento' => $row['segmento'],

    	]);



    }




}
