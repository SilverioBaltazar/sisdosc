<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class regTipometaModel extends Model
{
    protected $table = "SIEVAD_CAT_TIPO_ACCION";
    protected  $primaryKey = 'TACCION_ID';
    public $timestamps = false;
    public $incrementing = false;
    protected $fillable = [
	    'TACCION_ID',
        'TACCION_DESC',
        'TACCION_STATUS',
        'TACCION_FECREG'
    ];
}
