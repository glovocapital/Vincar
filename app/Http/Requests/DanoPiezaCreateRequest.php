<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DanoPiezaCreateRequest extends FormRequest
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
            'dano_pieza_observaciones' => 'required',
            'pieza_id' => 'required',
            'tipo_dano_id' => 'required',
            'gravedad_id' => 'required',
        ];
    }
}
