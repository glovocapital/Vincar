<?php

namespace App\Exports;

use App\HistoricoVin;
// use Illuminate\Support\Facades\Crypt;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

use function GuzzleHttp\json_decode;

class HistoricoVinLoteExport implements FromArray, WithHeadings
{
    // /**
    // * @return \Illuminate\Support\Collection
    // */
    // public function collection()
    // {
    //     return HistoricoVin::all();
    // }

    protected $historico_vins;

    public function __construct(array $historico_vins)
    {
        $this->historico_vins = $historico_vins;
    }

    /**
    * @return \Illuminate\Support\Collection
    */

    public function array(): array
    {
        return $this->historico_vins;
    }

    public function headings(): array
    {
        return [
            'ID de registro',
            'Fecha del Registro',
            'Código',
            'ID de VIN',
            'Cliente',
            'Estado de inventario',
            'Usuario',
            'Origen',
            'Destino',
            'Descripción',
        ];
    }
}
