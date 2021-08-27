<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FormProcessCreateRequest extends FormRequest
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
            'name'         => 'required|min:3|unique:processes,name',
            'annexed'      => 'required|integer',
            'active'       => 'required',
        ];
    }

    public function messages()
    {
        return [
            'active.required'       =>  'El estatus es requerido.',
            'annexed.required'      =>  'El anexo es requerido.',
            'annexed.integer'       =>  'El anexo debe ser numerico.',
            'name.required'         =>  'El nombre es requerido.',
            'name.min'              =>  'El nombre debe contener un minimo de 3 caracteres.',
            'name.unique'           =>  'El nombre ingresado ya existe!',

        ];
    }
}
