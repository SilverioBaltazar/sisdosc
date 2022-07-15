<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class balanzaRequest extends FormRequest
{
    public function messages()
    {  
        return [  
            'periodo_id.required'      => 'El periodo fiscal es obligatorio.',
            'osc_id.required'          => 'Id de la OSC es obligatorio.',
            'num_id.required'          => 'No. de semestre es obligatorio.',
            'ids_dreef.required'       => 'Ingresos del semestre, donativos recibidos en efectivo es obligatorio.',
            'ids_drees.required'       => 'Ingresos del semestre, donativos recibidos en especie es obligatorio.',
            'ids_crecup.required'      => 'Ingresos del semestre, cuotas de recuperación es obligatorio.',
            'ids_agub.required'        => 'Ingresos del semestre, apoyos gubernamentales es obligatorio.',
            'ids_lding.required'       => 'Ingresos del semestre, demas ingresos es obligatorio.',
            'eds_ca.required'          => 'Egresos del semestre, costos asistenciales es obligatorio.',
            'eds_ga.required'          => 'Egresos del semestre, gastos de administración es obligatorio.',
            'eds_cf.required'          => 'Egresos del semestre, gastos financieros es obligatorio.',
            
            'reman_sem.required'       => 'Remanente del semestre es obligatorio.',
            'act_circ.required'        => 'Activo circulante es obligatorio.',
            'act_nocirc.required'      => 'Activo No circulante-Bienes es obligatorio.',
            'act_nocircinm.required'   => 'Activo No circulante-Inmuebles es obligatorio.', 
            'pas_acp.required'         => 'Pasivo a corto plazo es obligatorio.',
            'pas_alp.required'         => 'Pasivo a largo plazo es obligatorio.',
            'patrimonio.required'      => 'Patrimonio es obligatorio',
            'periodo_id1.required'     => 'El año es obligatorio.',
            'mes_id1.required'         => 'El mes es obligatorio.',
            'dia_id1.required'         => 'El día es obligatorio.'            
            //'trx_desc.regex'         => 'El nombre de la función contiene caracteres inválidos.'
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
            'periodo_id'   => 'required',
            'osc_id'       => 'required',
            'num_id'       => 'required', 
            'ids_dreef'    => 'required', 
            'ids_drees'    => 'required',      
            'ids_crecup'   => 'required',      
            'ids_agub'     => 'required',      
            'ids_lding'    => 'required',      
            'eds_ca'       => 'required',      
            'eds_ga'       => 'required',      
            'eds_cf'       => 'required',      
            
            'reman_sem'    => 'required',    
            'act_circ'     => 'required',     
            'act_nocirc'   => 'required',  
            'act_nocircinm'=> 'required',   
            'pas_acp'      => 'required',    
            'pas_alp'      => 'required',     
            'patrimonio'   => 'required',
            'periodo_id1'  => 'required', 
            'mes_id1'      => 'required',
            'dia_id1'      => 'required'
            //'trx_desc'   => 'min:1|max:100|required|regex:/(^([a-zA-z%()=.\s\d]+)?$)/i'
        ];
    }
} 
