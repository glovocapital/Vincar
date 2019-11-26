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
            'user_pass.confirmed' => 'Las contraseñas ingresadas no coinciden'
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
            'user_pass' => 'required|min:6|confirmed'
        ];
    }
}
