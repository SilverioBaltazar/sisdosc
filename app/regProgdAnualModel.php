<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class regProgdAnualModel extends Model
{
    protected $table      = "SIEVAD_DPA";
    protected $primaryKey = '[PERIORO_ID,FOLIO, PARTIDA]';
    public $timestamps    = false;
    public $incrementing  = false;
    protected $fillable   = [
        'FOLIO',
        'PARTIDA',
        'PERIODO_ID',
        'CIPREP_ID',
        'LGOB_COD',
        'DEPEN_ID1',
        'DEPEN_ID2',
        'DEPEN_ID3',
        'EPPROG_ID',
        'EPPROY_ID',
        'FECHA_ENTREGA',
        'FECHA_ENTREGA2',
        'FECHA_ENTREGA3',
        'PERIODO_ID1',
        'MES_ID1',
        'DIA_ID1',
        'MES_ID2',
        'PROGRAMA_ID',
        'PROGRAMA_DESC',
        'ACTIVIDAD_ID',
        'ACTIVIDAD_DESC',
        'OBJETIVO_ID',
        'OBJETIVO_DESC',
        'OPERACIONAL_DESC',
        'UMED_ID',
        'TACCION_ID',
        'MESP_01',
        'MESP_02',
        'MESP_03',
        'MESP_04',
        'MESP_05',
        'MESP_06',
        'MESP_07',
        'MESP_08',
        'MESP_09',
        'MESP_10',
        'MESP_11',
        'MESP_12',
        'MESC_01',
        'MESC_02',
        'MESC_03',
        'MESC_04',
        'MESC_05',
        'MESC_06',
        'MESC_07',
        'MESC_08',
        'MESC_09',
        'MESC_10',
        'MESC_11',
        'MESC_12',
        'TRIMP_01',
        'TRIMP_02',
        'TRIMP_03',
        'TRIMP_04',
        'TRIMC_01',
        'TRIMC_02',
        'TRIMC_03',
        'TRIMC_04',
        'TSEMP_01',
        'TSEMP_02',
        'TSEMC_01',
        'TSEMC_02',
        'TOTP_01',
        'TOTP_02',
        'TOTC_01',
        'TOTC_02',
        'MES_P01',
        'MES_P02',
        'MES_P03',
        'MES_P04',
        'MES_P05',
        'MES_P06',
        'MES_P07',
        'MES_P08',
        'MES_P09',
        'MES_P10',
        'MES_P11',
        'MES_P12',
        'TRIM_P01',
        'TRIM_P02',
        'TRIM_P03',
        'TRIM_P04',
        'SEM_P01',
        'SEM_P02',
        'TOT_P01',
        'SEMAF_01',
        'SEMAF_02',
        'SEMAF_03',
        'SEMAF_04',
        'SEMAF_05',
        'SEMAF_06',
        'SEMAF_07',
        'SEMAF_08',
        'SEMAF_09',
        'SEMAF_10',
        'SEMAF_11',
        'SEMAF_12',
        'SEMAFT_01',
        'SEMAFT_02',
        'SEMAFT_03',
        'SEMAFT_04',
        'SEMAFS_01',
        'SEMAFS_02',
        'SEMAFA_01',
        'SOPORTE_ID',
        'SOPORTE_01',
        'SOPORTE_02',
        'SOPORTE_03',
        'SOPORTE_04',
        'SOPORTE_05',
        'SOPORTE_06',
        'SOPORTE_07',
        'SOPORTE_08',        
        'SOPORTE_09',
        'SOPORTE_10',
        'SOPORTE_11',
        'SOPORTE_12',                  
        'OBS_01',
        'OBS_02',
        'OBS_03',
        'OBS_04',
        'OBS_05',
        'OBS_06',
        'OBS_07',
        'OBS_08',
        'OBS_09',
        'OBS_10',
        'OBS_11',
        'OBS_12',                        
        'STATUS_1',
        'STATUS_2',
        'FECREG',
        'FECREG2',
        'IP',
        'LOGIN',
        'FECHA_M',
        'FECHA_M2',
        'IP_M',
        'LOGIN_M'
    ];

    //***************************************//
    // *** Como se usa el query scope  ******//
    //***************************************//
    public function scopeName($query, $name)
    {
        if($name)
            return $query->where('PROGRAMA_DESC', 'LIKE', "%$name%");
    }

    public function scopeActi($query, $acti)
    {
        if($acti)
            return $query->where('ACTIVIDAD_DESC', 'LIKE', "%$acti%");
    }

    public function scopeBio($query, $bio)
    {
        if($bio)
            return $query->where('OBJETIVO_DESC', 'LIKE', "%$bio%");
    }

    //********* Obtiene valores de meses programados **********************//
    public static function Obtvalores($id,$id2){
        return regProgdAnualModel::select('MESP_01','MESP_02','MESP_03','MESP_04','MESP_05','MESP_06',
                                          'MESP_07','MESP_08','MESP_09','MESP_10','MESP_11','MESP_12',
                                          'TRIMP_01','TRIMP_02','TRIMP_03','TRIMP_04',
                                          'TSEMP_01','TSEMP_02','TOTP_01','TOTP_02',
                                          'MESC_01','MESC_02','MESC_03','MESC_04','MESC_05','MESC_06',
                                          'MESC_07','MESC_08','MESC_09','MESC_10','MESC_11','MESC_12',
                                          'TRIMC_01','TRIMC_02','TRIMC_03','TRIMC_04',
                                          'TSEMC_01','TSEMC_02','TOTC_01','TOTC_02'
                                           )
                                   ->where([['FOLIO','=',$id],['PARTIDA','=',$id2]])
                                   ->get();
    }    
 
}
