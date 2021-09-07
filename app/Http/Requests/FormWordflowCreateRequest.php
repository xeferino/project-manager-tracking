<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FormWordflowCreateRequest extends FormRequest
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
            'name'          => 'required|min:3|unique:wordflows,name',
            'description'   => 'nullable|min:10',
            'process'       => 'required|unique:wordflows,process_id',
            'steps'         => 'required',
        ];
    }

    public function messages()
    {
        return [
            'description.min'       =>  'La descripcion debe tener un minimo de 10 caracteres.',
            'process.required'      =>  'El proceso es requerido.',
            'process.unique'        =>  'El proceso debe ser unico por flujograma.',
            'name.required'         =>  'El nombre es requerido.',
            'name.min'              =>  'El nombre debe contener un minimo de 3 caracteres.',
            'name.unique'           =>  'El nombre ingresado ya existe!',
            'steps.required'        =>  'Los pasos son requeridos, selecione un nuevo departamento y presione click en el boton [+]',
        ];
    }
}
