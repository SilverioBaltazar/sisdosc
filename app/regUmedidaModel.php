<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class regUmedidaModel extends Model
{
    protected $table      = "SIEVAD_CAT_UMEDIDA";
    protected $primaryKey = 'UMED_ID';
    public $timestamps    = false;
    public $incrementing  = false;
    protected $fillable   = [
        'UMED_ID',        
        'UMED_DESC',
        'UMED_STATUS', //S ACTIVO      N INACTIVO
        'UMED_FECREG'
    ];
}