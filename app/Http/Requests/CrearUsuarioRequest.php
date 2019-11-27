<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CrearUsuarioRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */

    public function messages()
    {
        return [
            'user_pass.required' => 'La contraseña es un campo requerido',
            'user_pass.min' => 'La contraseña debe poseer un minimo de 6 caracteres',
            'user_pass.confirmed' => 'Las contraseñas ingresadas no coinciden',
            'user_nombre.required' => 'El nombre del usuario es un campo requerido',
            'user_nombre.min' => 'El nombre del usuario debe poseer un minimo de 2 caracteres',
            'user_apellido.required' => 'El apellido del usuario es un campo requerido',
            'user_apellido.min' => 'El apellido del usuario debe poseer un minimo de 2 caracteres',
            'user_rut.required' => 'El apellido del usuario es un campo requerido',
            'user_rut.min' => 'El apellido del usuario debe poseer un minimo de 2 caracteres'
        ];
    }
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
            'user_pass' => 'required|min:6|confirmed',
            'user_nombre' => 'required|min:2',
            'user_apellido' => 'required|min:2',
            'user_rut' => 'required|min:8'
        ];
    }
}
