<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InspeccionCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if(isset($_REQUEST['submit_1'])){
            return [
                'inspeccion.inspeccion_fecha' => 'required',
                'inspeccion.vin_id' => 'required',
                'inspeccion.cliente_id' => 'required',
                'inspeccion.vin_estado_inventario_id' => 'required',
            ];
        } elseif(isset($_REQUEST['submit_2'])){
            return [
                'inspeccion.inspeccion_fecha' => 'required',
                'inspeccion.vin_id' => 'required',
                'inspeccion.cliente_id' => 'required',
                'inspeccion.vin_estado_inventario_id' => 'required',
                'dano_pieza.pieza_id' => 'required',
                'dano_pieza.tipo_dano_id' => 'required',
                'dano_pieza.gravedad_id' => 'required',
                'dano_pieza.dano_pieza_observaciones' => 'required',
            ];
        } else {
            return [
                'inspeccion.inspeccion_fecha' => 'required',
                'inspeccion.vin_id' => 'required',
                'inspeccion.cliente_id' => 'required',
                'inspeccion.vin_estado_inventario_id' => 'required',
                'dano_pieza.dano_pieza_observaciones' => 'required',
                'dano_pieza.pieza_id' => 'required',
                'dano_pieza.tipo_dano_id' => 'required',
                'dano_pieza.gravedad_id' => 'required',
                'foto.foto_fecha' => 'required',
                'foto.foto_descripcion' => 'required',
                'foto.foto_coord_lat' => 'required',
                'foto.foto_coord_lon' => 'required',
                'foto_nombre_archivo' => 'required',
            ];
        }
    }

    public function messages()
    {
        return [
            // 'name.required' => 'El :attribute es obligatorio.',
            // 'price.required' => 'Añade un :attribute al producto',
            // 'price.min' => 'El :attribute debe ser mínimo 0'
        ];
    }

    public function attributes()
    {
        return [
            // 'name' => 'nombre del producto',
            // 'price' => 'precio de venta',
        ];
    }


}
