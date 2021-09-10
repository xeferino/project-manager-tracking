<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FormProjectCreateRequest extends FormRequest
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
        if ($this->input('action') == 'created') {
            return [
                'name'          => 'required|min:3|unique:projects,name',
                'description'   => 'nullable|min:10',
                'process'       => 'required',
            ];
        }else{
            return [
                'name'          => 'required|min:3|unique:projects,name',
                'description'   => 'nullable|min:10',
                'process'       => 'required',
                'annexed'       => 'required'
            ];
        }
    }

    public function messages()
    {
        return [
            'description.min'       =>  'La descripcion debe tener un minimo de 10 caracteres.',
            'process.required'      =>  'El proceso es requerido.',
            'name.required'         =>  'El nombre es requerido.',
            'name.min'              =>  'El nombre debe contener un minimo de 3 caracteres.',
            'name.unique'           =>  'El nombre ingresado ya existe!',
            'annexed.required'      =>  'Debe agregar los anexos, para proceder al envio del proyecto',
        ];
    }
}
