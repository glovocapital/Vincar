<?php

namespace App\Exports;

use App\Vin;
use GuzzleHttp\Psr7\Request;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BusquedaVinsExport implements FromArray, WithHeadings
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
            'Vin ID',
            'Código',
            'Patente',
            'Marca',
            'Modelo',
            'Color',
            'Segmento',
            'Motor',
            'Propietario',
            'Fecha de Ingreso',
            'Estado de Inventario',
            'Patio',
            'Bloque',
            'ID Ubcación',
            'Fila',
            'Columna'
        ];
    }
}
