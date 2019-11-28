<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmpresaRequest extends FormRequest
{
    public function messages()
    {
        return [
            'empresa_rut.required' => 'La contraseña es un campo requerido',
            'empresa_rut.min' => 'La contraseña debe poseer un minimo de 8 caracteres',
            'empresa_nombre.required' => 'El nombre de la empresa es un campo requerido',
            'empresa_direccion.required' => 'La direccion de la empresa es un campo requerido'
        ];
    }




    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'empresa_rut' => 'required|min:8',
            'empresa_nombre' => 'required',
            'empresa_direccion' => 'required'
        ];
    }
}
