<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class regusuariosModel extends Model
{
    protected $table = 'SIEVAD_CTRL_ACCESO';
    protected  $primaryKey = ['LOGIN','PASSWORD'];
    public $timestamps = false;
    public $incrementing = false;
    protected $fillable = [
	    'N_PERIODO',
        'CVE_PROGRAMA',
        'FOLIO',
        'DEPEN_ID',
        'LOGIN',
        'PASSWORD',
        'AP_PATERNO',
        'AP_MATERNO',
        'NOMBRES',
        'NOMBRE_COMPLETO',
        'FECHA_NACIMIENTO',
        'TELEFONO',
        'CVE_NIVEL',
        'CVE_MUNICIPIO',
        'CVE_ENTIDAD_FEDERATIVA',
        'CVE_ARBOL',
        'TIPO_USUARIO',
        'STATUS_1',
        'STATUS_2',
        'IP',
        'FECHA_REGISTRO',
        'OSC_DESC'
    ];

    //***************************************//
    // *** Como se usa el query scope  ******//
    //***************************************//
    public function scopeName($query, $name)
    {
        if($name)
            return $query->where('NOMBRE_COMPLETO', 'LIKE', "%$name%");
    }

    public function scopeIdd($query, $idd)
    {
        if($idd)
            return $query->where('CVE_ARBOL', '=', $idd);
    }
    public function scopeLogin($query, $login) 
    {
        if($login)
            return $query->where('LOGIN', 'LIKE', "%$login%");
    }

    public function scopeNameosc($query, $nameosc)
    {
        if($nameosc) 
            return $query->where('OSC_DESC', 'LIKE', "%$nameosc%");
    } 

}
 