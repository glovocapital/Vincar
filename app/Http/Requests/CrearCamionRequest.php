<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class CrearCamionRequest extends FormRequest
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
            'camion_patente' => 'required',
            'camion_anio' => [
                'required',
                'numeric',
                'min:1980',
            ],
            'camion_fecha_revision' => [
                'required', 
                'after:'.Carbon::now(),
            ],
            'camion_fecha_circulacion' => [
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
            'camion_modelo' => 'required',
            'camion_foto_documento' => [
                'required', 
                'between:100,3000',
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
            'camion_patente.required' => 'La patente es un campo obligatorio.',
            'camion_anio.required' => 'El año es un campo obligatorio.',
            'camion_fecha_revision.required' => 'Fecha de revisión es obligatoria.',
            'camion_fecha_circulacion.required' => 'Fecha de circulación es obligatoria.',
            'empresa_id.required' => 'Debe seleccionar una empresa.',
            'camion_modelo.required' => 'Modelo de camión es un campo obligatorio.',
            'camion_foto_documento.required' => 'La foto del documento del camión es obligatoria.',
            'camion_anio.numeric' => 'El año del documento debe ser numérico.',
            'camion_anio.min' => 'El año del documento debe ser mínimo 1980.',
            'camion_fecha_revision.after' => 'Fecha de revisión vencida. Documento vencido.',
            'camion_fecha_circulacion.after' => 'Fecha de Circulación vencida. Documento vencido.',
            'marca_id.numeric' => 'Error de tipo al enviar la marca.',
            'empresa_id.numeric' => 'Error de tipo al enviar la empresa.',
            'camion_foto_documento.between' => 'Tamaño de archivo inválido. Debe ser mínimo 100 Kb y máximo 3000 Kb.',
        ];
    }
}
