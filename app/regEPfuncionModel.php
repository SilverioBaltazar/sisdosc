<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class regEPfuncionModel extends Model
{
   protected $table      = "SIEVAD_CAT_EP_FUNCION";
    protected $primaryKey = 'EPFUN_ID';
    public $timestamps    = false;
    public $incrementing  = false;
    protected $fillable   = [
        'EPFUN_ID',
        'EPFUN_DESC',
        'EPFUN_STATUS',
        'EPFUN_FECREG'   
    ];
}
