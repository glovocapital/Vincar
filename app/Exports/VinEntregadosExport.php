<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

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
            'VIN Codigo',
            'Patente',
            'Color',
            'Fecha de ingreso a patio',
            'Fecha de agendamiento para entrega',
            'Fecha de entrega',
            'Empresa'
        ];
    }
}
