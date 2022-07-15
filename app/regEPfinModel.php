<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class regEPfinModel extends Model
{
    protected $table      = "SIEVAD_CAT_EP_FIN";
    protected $primaryKey = 'EPFIN_ID';
    public $timestamps    = false;
    public $incrementing  = false;
    protected $fillable   = [
        'EPFIN_ID',
        'EPFIN_DESC',
        'EPFIN_STATUS',
        'EPFIN_FECREG'
    ];

    //***************************************//
    // *** Como se usa el query scope  ******//
    //***************************************//
    public function scopePer($query, $per)
    {
        if($per)
            return $query->where('PERIODO_ID', '=', "$per");
    }

    public function scopeIapp($query, $iapp)
    {
        if($iapp)
            return $query->where('IAP_ID', '=', "$iapp");
    }

    public function scopeBio($query, $bio)
    {
        if($bio)
            return $query->where('IAP_OBJSOC', 'LIKE', "%$bio%");
    } 

}