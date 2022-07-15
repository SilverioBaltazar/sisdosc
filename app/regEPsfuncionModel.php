<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class regEPsfuncionModel extends Model
{
   protected $table      = "SIEVAD_CAT_EP_SUBFUNCION";
    protected $primaryKey = 'EPSFUN_ID';
    public $timestamps    = false;
    public $incrementing  = false;
    protected $fillable   = [
        'EPSFUN_ID',
        'EPSFUN_DESC',
        'EPSFUN_STATUS',
        'EPSFUN_FECREG'   
    ];
}
