<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class CrearRemolqueRequest extends FormRequest
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
        return [
            'remolque_patente' => 'required',
            'remolque_anio' => [
                'required',
                'numeric',
                'min:1980',
            ],
            'remolque_fecha_revision' => [
                'required', 
                'after:'.Carbon::now(),
            ],
            'remolque_fecha_circulacion' => [
                'required', 
                'after:'.Carbon::now(),
            ],
            'marca_id' => [
                'numeric',
            ],
            'empresa_id' => [
                'required',
                'numeric',
            ],
            'remolque_modelo' => 'required',
            'remolque_foto_documento' => [
                'required', 
                'between:100,3000',
            ],
            'remolque_capacidad' => [
                'required',
                'numeric',
                'min:1',
            ],
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */

    public function messages()
    {
        return [
            'remolque_patente.required' => 'La patente es un campo obligatorio.',
            'remolque_anio.required' => 'El año es un campo obligatorio.',
            'remolque_fecha_revision.required' => 'Fecha de revisión es obligatoria.',
            'remolque_fecha_circulacion.required' => 'Fecha de circulación es obligatoria.',
            'empresa_id.required' => 'Debe seleccionar una empresa.',
            'remolque_modelo.required' => 'Modelo de remolque es un campo obligatorio.',
            'remolque_foto_documento.required' => 'La foto del documento del remolque es obligatoria.',
            'remolque_capacidad.required' => 'La capacidad del remolque es un campo obligatorio.',
            'remolque_anio.numeric' => 'El año del documento debe ser numérico.',
            'remolque_anio.min' => 'El año del documento debe ser mínimo 1980.',
            'remolque_fecha_revision.after' => 'Fecha de revisión vencida. Documento vencido.',
            'remolque_fecha_circulacion.after' => 'Fecha de Circulación vencida. Documento vencido.',
            'marca_id.numeric' => 'Error de tipo al enviar la marca.',
            'empresa_id.numeric' => 'Error de tipo al enviar la empresa.',
            'remolque_capacidad.numeric' => 'Error de tipo al enviar la capacidad.',
            'remolque_capacidad.min' => 'El remolque debe poder transportar al menos 1 vehículo.',
            'remolque_foto_documento.between' => 'Tamaño de archivo inválido. Debe ser mínimo 100 Kb y máximo 3000 Kb.',
        ];
    }
}
