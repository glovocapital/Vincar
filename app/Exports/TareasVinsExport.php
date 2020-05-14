<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Vin;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;


class TareasVinsExport implements FromArray, WithHeadings
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
            'Id Tarea',
            'VIN',
            'Patente',
            'Tarea',
            'Usuario Responsable',
            'Destino',
            'Fecha finalizacion solicitada',
            'Hora de termino solicitado',
            'Fecha y hora de finalizacion real'
        ];
    }
}
