<?php

namespace App\Exports;

use App\Vin;
use GuzzleHttp\Psr7\Request;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class VinEntregadosExport implements FromArray, WithHeadings
{
    protected $results;

    public function __construct(array $resultados)
    {
        $this->results = $resultados;
    }

    /**
    * @return \Illuminate\Support\Collection
    */

    public function array(): array
    {
        return $this->results;
    }

    public function headings(): array
    {
        return [
            'VIN',
            'VIN Codigo',
            'Patente',
            'Modelo',
            'Marca',
            'Color',
            'Motor',
            'Segmento',
            'Fecha de ingreso a patio',
            'user_id',
            'created_at',
            'updated_at',
            'vin_estado_inventario_id',
            'vin_sub_estado_inventario_id',
            'vin_predespacho',
            'Fecha de entrega',
            'Fecha de agendamiento para entrega'

        ];
    }
}
