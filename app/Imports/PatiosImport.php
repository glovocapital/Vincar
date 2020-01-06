<?php

namespace App\Imports;

use App\Bloque;
use App\Patio;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToArray;

class PatiosImport implements ToArray
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function array(array $rows)
    {
        foreach ($rows as $k => $v){
            if($k != 0){
                $patio = Patio::whereRaw('upper(patio_nombre) like (?)',strtoupper($v[0]))->first();
                
                if(isset($patio)){
                    try {
                        DB::beginTransaction();
                        $patio->patio_bloques += 1;
                        $patio->save();

                        $existe = Bloque::whereRaw('upper(bloque_nombre) like (?)',strtoupper($v[1]))
                            ->where('patio_id', $patio->patio_id)
                            ->first();
                        
                        if(!$existe){
                            $bloque = new Bloque();

                            $bloque->bloque_nombre = $v[1];
                            $bloque->bloque_filas = $v[2];
                            $bloque->bloque_columnas = $v[3];
                            $bloque->bloque_coord_lat = $v[4];
                            $bloque->bloque_coord_lon = $v[5];
                            $bloque->patio_id = $patio->patio_id;

                            $bloque->save();
                        }
                        DB::commit();
                    } catch (\Throwable $th) {
                        DB::rollBack();
                        // return redirect()->route('inspeccion.create')->with('error-msg', 'Error anexando daño de pieza. Inspección no almacenada');
                    }
                } else {
                    try {
                        DB::beginTransaction();
                        $patio = new Patio();

                        $patio->patio_nombre = $v[0];
                        $patio->patio_bloques = 1;
                        $patio->patio_coord_lat = $v[4];
                        $patio->patio_coord_lon = $v[5];
                        $patio->patio_direccion = "Actualizar dirección";

                        $patio->save();

                        $existe = Bloque::whereRaw('upper(bloque_nombre) like (?)',strtoupper($v[1]))
                            ->where('patio_id', $patio->patio_id)
                            ->first();
                        
                        if(!$existe){
                            $bloque = new Bloque();

                            $bloque->bloque_nombre = $v[1];
                            $bloque->bloque_filas = $v[2];
                            $bloque->bloque_columnas = $v[3];
                            $bloque->bloque_coord_lat = $v[4];
                            $bloque->bloque_coord_lon = $v[5];
                            $bloque->patio_id = $patio->patio_id;

                            $bloque->save();
                        }
                        DB::commit();
                    } catch (\Throwable $th) {
                        DB::rollBack();
                        // return redirect()->route('inspeccion.create')->with('error-msg', 'Error anexando daño de pieza. Inspección no almacenada');
                    }
                }
            }
        }
    }
}
