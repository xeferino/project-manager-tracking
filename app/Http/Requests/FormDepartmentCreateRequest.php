<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FormDepartmentCreateRequest extends FormRequest
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
            'name'         => 'required|min:3|unique:departments,name',
            'active'        => 'required',
        ];
    }

    public function messages()
    {
        return [
            'active.required'       =>  'El estatus es requerido.',
            'name.required'         =>  'El nombre es requerido.',
            'name.min'              =>  'El nombre debe contener un minimo de 3 caracteres.',
            'name.unique'           =>  'El nombre ingresado ya existe!',

        ];
    }
}
