<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class regEPproyectoModel extends Model
{
    protected $table      = "SIEVAD_CAT_EP_PROYECTO";
    protected $primaryKey = 'CONDICION_ID';
    public $timestamps    = false;
    public $incrementing  = false;
    protected $fillable   = [
        'EPPROY_ID',
        'EPPROY_DESC',
        'EPPROY_STATUS',
        'EPPROY_FECREG'
    ];
}