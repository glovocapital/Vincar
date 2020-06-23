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
            'Motor',
            'Segmento',
            'Fecha de Ingreso',
            'Propietario',
            'Estado de Inventario',
            'Fecha de Entrega',
            'Fecha de Agendado',
            'Patio',
            'Bloque',
            'Fila',
            'Columna'
        ];
    }
}
