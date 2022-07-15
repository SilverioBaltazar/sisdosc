<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class regEPprogramaModel extends Model
{
    protected $table      = "SIEVAD_CAT_EP_PROGRAMA";
    protected $primaryKey = ['EPPROG_ID','PILAR_ID'];
    public $timestamps    = false;
    public $incrementing  = false;
    protected $fillable   = [
        'EPPROG_ID',
        'EPPROG_DESC',
        'PILAR_ID',
        'PILAR_DESC',
        'EPPROG_STATUS',
        'EPPROG_FECREG'
    ];
}