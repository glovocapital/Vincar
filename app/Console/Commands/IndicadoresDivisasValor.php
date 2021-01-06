<?php

namespace App\Console\Commands;

use App\DivisaValor;
use Illuminate\Console\Command;

class IndicadoresDivisasValor extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'indicadores:divisas_valor';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Consulta un servicio web que entrega los indicadores de divisas en formato JSON';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // PARÁMETROS DE CONEXIÓN A BASE DE DATOS
        // $host = 'localhost';
        // $puerto = '5432';
        // $baseDatos = 'vincar';
        // $usuario = 'postgres';
        // $password = 'super123';
        // FIN DE LOS PARÁMETROS DE CONEXIÓN A BASE DE DATOS

        // ESTA VARIABLE DEFINE SI VOY A ACTUALIZAR EL AÑO COMPLETO, O VOY A ACTUALIZAR EL DÍA
        // VALORES POSIBLES: ANUAL o DIARIO
        // $tipoActualizacion = 'ANUAL';
        $tipoActualizacion = 'DIARIO';

        /*
        Según la tabla "divisas"
        1.- USD
        2.- CLP
        3.- UF
        4.- UTM
        5.- EUR
        */

        switch ($tipoActualizacion) {
            case 'ANUAL':
                $this->updateUFAnual();
                $this->updateDolarAnual();
                $this->updateUTMAnual();
                break;
            case 'DIARIO':
                $this->updateDiario();
                break;
            default:
                echo "El tipo de actualización seleccionado no es correcto. Debe ser 'ANUAL' o 'DIARIO'.";
                break;
        }
    }

    protected function updateUFAnual(){
        $url =  'https://mindicador.cl/api/uf/2020';
        if ( ini_get('allow_url_fopen') ) {
            $json = file_get_contents($url);
        } else {
            //De otra forma utilizamos cURL
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $json = curl_exec($curl);
            curl_close($curl);
        }
        $this->guardaValores($json, 3);
    }

    protected function updateDolarAnual(){
        $url =  'https://mindicador.cl/api/dolar/2020';
        if ( ini_get('allow_url_fopen') ) {
            $json = file_get_contents($url);
        } else {
            //De otra forma utilizamos cURL
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $json = curl_exec($curl);
            curl_close($curl);
        }
        $this->guardaValores($json, 1);
    }

    protected function updateUTMAnual(){
        $url =  'https://mindicador.cl/api/utm/2020';
        if ( ini_get('allow_url_fopen') ) {
            $json = file_get_contents($url);
        } else {
            //De otra forma utilizamos cURL
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $json = curl_exec($curl);
            curl_close($curl);
        }
        $this->guardaValores($json, 4);
    }

    protected function guardaValores($json, $tipoDivisa){
        $valor = json_decode($json);

        foreach($valor->serie as $dia){
            $divisa = DivisaValor::where('divisa_id', $tipoDivisa)
                ->where('divisa_valor_fecha', date_format(date_create($dia->fecha), 'Y-m-d'))
                ->first();


            if ($divisa) {
                $divisa->update(['divisa_valor_valor' => $dia->valor]);
            } else {
                $divisaValor = new DivisaValor();

                $divisaValor->divisa_id = $tipoDivisa;
                $divisaValor->divisa_valor_fecha = date_format(date_create($dia->fecha), 'Y-m-d');
                $divisaValor->divisa_valor_valor = $dia->valor;

                $divisaValor->save();
            }
        }
    }

    protected function updateDiario()
    {
        $apiUrl = 'https://mindicador.cl/api';
        //Es necesario tener habilitada la directiva allow_url_fopen para usar file_get_contents
        if ( ini_get('allow_url_fopen') ) {
            $json = file_get_contents($apiUrl);
        } else {
            //De otra forma utilizamos cURL
            $curl = curl_init($apiUrl);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $json = curl_exec($curl);
            curl_close($curl);
        }

        $indicador = json_decode($json);
        $tipoDivisa = 1; // Dolar

        $divisaDolar = DivisaValor::where('divisa_id', $tipoDivisa)
            ->where('divisa_valor_fecha', date_format(date_create($indicador->dolar->fecha), 'Y-m-d'))
            ->first();

        if ($divisaDolar) {
            $divisaDolar->update(['divisa_valor_valor' => $indicador->dolar->valor]);
        } else {
            $divisaValor = new DivisaValor();

            $divisaValor->divisa_id = $tipoDivisa;
            $divisaValor->divisa_valor_fecha = date_format(date_create($indicador->dolar->fecha), 'Y-m-d');
            $divisaValor->divisa_valor_valor = $indicador->dolar->valor;

            $divisaValor->save();
        }

        $tipoDivisa = 3; // UF

        $divisaUf = DivisaValor::where('divisa_id', $tipoDivisa)
            ->where('divisa_valor_fecha', date_format(date_create($indicador->uf->fecha), 'Y-m-d'))
            ->first();

        if ($divisaUf) {
            $divisaUf->update(['divisa_valor_valor' => $indicador->uf->valor]);
        } else {
            $divisaValor = new DivisaValor();

            $divisaValor->divisa_id = $tipoDivisa;
            $divisaValor->divisa_valor_fecha = date_format(date_create($indicador->uf->fecha), 'Y-m-d');
            $divisaValor->divisa_valor_valor = $indicador->uf->valor;

            $divisaValor->save();
        }

        $tipoDivisa = 4; // UTM

        $divisaUtm = DivisaValor::where('divisa_id', $tipoDivisa)
            ->where('divisa_valor_fecha', date_format(date_create($indicador->utm->fecha), 'Y-m-d'))
            ->first();

        if ($divisaUtm) {
            $divisaUtm->update(['divisa_valor_valor' => $indicador->utm->valor]);
        } else {
            $divisaValor = new DivisaValor();

            $divisaValor->divisa_id = $tipoDivisa;
            $divisaValor->divisa_valor_fecha = date_format(date_create($indicador->utm->fecha), 'Y-m-d');
            $divisaValor->divisa_valor_valor = $indicador->utm->valor;

            $divisaValor->save();
        }

        //echo '<br />El valor actual de la UF es $' . $indicador->uf->valor;
        //echo '<br />El valor actual del Dólar observado es $' . $indicador->dolar->valor;
        //echo '<br />El valor actual del Dólar acuerdo es $' . $indicador->dolar_intercambio->valor;
        //echo '<br />El valor actual del Euro es $' . $indicador->euro->valor;
        //echo '<br />El valor actual del IPC es ' . $indicador->ipc->valor;
        //echo '<br />El valor actual de la UTM es $' . $indicador->utm->valor;
        //echo '<br />El valor actual del IVP es $' . $indicador->ivp->valor;
        //echo '<br />El valor actual del Imacec es ' . $indicador->imacec->valor;
    }
}
