<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class informelabRequest extends FormRequest
{
    public function messages()
    {
        return [
            //'iap_id.required'       => 'IAP es obligatorio.',            
            //'periodo_id1.required'  => 'Año de elaboración es obligatorio.',
            'mes_id2.required'        => 'Mes de avance es obligatorio.',
            //'dia_id1.required'      => 'Dia de elaboración es obligatorio.',
            //'programa_desc.min'     => 'Programa es de mínimo 1 caracter.',
            //'programa_desc.max'     => 'Programa es de máximo 500 caracteres.',
            //'programa_desc.required'=> 'Programa es obligatorio.',
            //'actividad_desc.min'    => 'Acitividad es de mínimo 1 caracter.',
            //'actividad_desc.max'    => 'Acitividad es de máximo 500 caracteres.',
            //'actividad_desc.required'=> 'Acitividad es obligatoria.',
            //'objetivo_desc.min'     => 'Objetivo es de mínimo 1 caracter.',
            //'objetivo_desc.max'     => 'Objetivo es de máximo 500 caracteres.',
            //'objetivo_desc.required'=> 'Objetivo es obligatoria.',
            //'umedida_id.required'   => 'Unidad de medida es obligatoria.',
            //'iap_colonia.min'       => 'La colonia es de mínimo 1 caracter.',            
            'avance.required'         => 'Avance del mes es obligatorio.',
            'avance.numeric'          => 'Avance del mes debé ser numerico.'
        ];
    }
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
            //'periodo_id1'    => 'required',
            'mes_id2'          => 'required',
            //'dia_id1'        => 'required',
            //'programa_desc'  => 'required|min:1|max:500',
            //'actividad_desc' => 'required|min:1|max:500',
            //'objetivo_desc'  => 'required|min:1|max:500',
            //'umedida_id'     => 'required',
            'avance'           => 'required'
            //'accion'         => 'required|regex:/(^([a-zA-z%()=.\s\d]+)?$)/i',
            //'medios'         => 'required|regex:/(^([a-zA-z\s\d]+)?$)/i'
            //'rubro_desc'     => 'min:1|max:80|required|regex:/(^([a-zA-zñÑ%()=.\s\d]+)?$)/iñÑ'
        ];
    }
}
