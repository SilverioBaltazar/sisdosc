<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class regAniosModel extends Model
{
    protected $table      = "SIEVAD_CAT_ANIOS";
    protected $primaryKey = 'ANIO_ID';
    public $timestamps    = false;
    public $incrementing  = false;
    protected $fillable   = [
        'ANIO_ID',
        'ANIO_DESC',
        'ANIO_STATUS', //S ACTIVO      N INACTIVO
        'ANIO_FECREG'
    ];
}