<?php
//**************************************************************/
//* File:       SoportesController.php
//* Función:    Registro de soportes documentales del Programa anual
//* Autor:      Ing. Silverio Baltazar Barrientos Zarate
//* Modifico:   Ing. Silverio Baltazar Barrientos Zarate
//* Fecha act.: mayo 2022
//* @Derechos reservados. Gobierno del Estado de México
//*************************************************************/
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\informelabRequest;
use App\Http\Requests\informelab1Request;
use App\Http\Requests\soportedet1Request;
use App\Http\Requests\soportedet2Request;
use App\Http\Requests\soportedet3Request;
use App\Http\Requests\soportedet4Request;
use App\Http\Requests\soportedet5Request;
use App\Http\Requests\soportedet6Request;
use App\Http\Requests\soportedet7Request;
use App\Http\Requests\soportedet8Request;
use App\Http\Requests\soportedet9Request;
use App\Http\Requests\soportedet10Request;
use App\Http\Requests\soportedet11Request;
use App\Http\Requests\soportedet12Request;


use App\regBitacoraModel;
use App\regdepenModel;
use App\regUmedidaModel;
use App\regPeriodosModel;
use App\regMesesModel;
use App\regDiasModel;
use App\regAniosModel;
use App\regTipometaModel;
use App\regEPprogramaModel;
use App\regEPproyectoModel;
use App\regProgeAnualModel;
use App\regProgdAnualModel;

// Exportar a excel 
use App\Exports\ExportProgtrabExcel;
use Maatwebsite\Excel\Facades\Excel;
// Exportar a pdf
use PDF;
//use Options;

class soportesController extends Controller
{

    public function actionBuscarSoporte(Request $request)
    {
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $ip           = session()->get('ip');
        $arbol_id     = session()->get('arbol_id');
        $depen_id     = session()->get('depen_id');

        $regumedida   = regUmedidaModel::select('UMED_ID','UMED_DESC')
                        ->orderBy('UMED_ID','asc')
                        ->get(); 
        $regepprog    = regEPprogramaModel::select('EPPROG_ID','EPPROG_DESC')
                        ->orderBy('EPPROG_ID','asc')
                        ->get();                         
        $regepproy    = regEPproyectoModel::select('EPPROy_ID','EPPROy_DESC')
                        ->orderBy('EPPROy_ID','asc')
                        ->get();                                                 
        $regperiodos  = regPeriodosModel::select('PERIODO_ID', 'PERIODO_DESC')
                        ->get();  
        $regmeses     = regMesesModel::select('MES_ID','MES_DESC')
                        ->get();      
        $regdias      = regDiasModel::select('DIA_ID','DIA_DESC')
                        ->get(); 
        $totactivs    = regProgeAnualModel::join('SIEVAD_DPA','SIEVAD_DPA.FOLIO', '=','SIEVAD_EPA.FOLIO')
                        ->select(   'SIEVAD_EPA.PERIODO_ID','SIEVAD_EPA.FOLIO')
                        ->selectRaw('COUNT(*) AS TOTacciones o metas')
                        ->groupBy(  'SIEVAD_EPA.PERIODO_ID','SIEVAD_EPA.FOLIO')
                        ->get();                         
        //**************************************************************//
        // ***** busqueda https://github.com/rimorsoft/Search-simple ***//
        // ***** video https://www.youtube.com/watch?v=bmtD9GUaszw   ***//                            
        //**************************************************************//
        $folio = $request->get('folio');   
        $name  = $request->get('name');           
        $acti  = $request->get('acti');  
        $bio   = $request->get('bio');    
        $nameiap = $request->get('nameiap');  
        if(session()->get('rango') !== '0'){    
            $regdepen     = regdepenModel::select('DEPEN_ID', 'DEPEN_DESC')
                            ->where('DEPEN_STATUS','1')
                            ->get();                                 
            $regprogeanual= regProgeAnualModel::join('SIEVAD_CAT_DEPENDENCIAS','SIEVAD_CAT_DEPENDENCIAS.DEPEN_ID','=',
                                                                               'SIEVAD_EPA.DEPEN_ID')
                            ->select( 'SIEVAD_CAT_DEPENDENCIAS.DEPEN_DESC','SIEVAD_EPA.*')
                            ->orderBy('SIEVAD_EPA.PERIODO_ID','ASC')
                            ->orderBy('SIEVAD_EPA.DEPEN_ID'  ,'ASC')
                            ->orderBy('SIEVAD_EPA.FOLIO'     ,'ASC')
                            //->name($name)     //Metodos personalizados es equvalente a ->where('DEPEN_DESC', 'LIKE', "%$name%");
                            //->acti($acti)     //Metodos personalizados
                            //->bio($bio)       //Metodos personalizados
                            ->folio($folio)     //Metodos personalizados     
                            ->nameiap($nameiap) //Metodos personalizados                                                                      
                            ->paginate(50);
        }else{
            $regdepen     = regdepenModel::select('DEPEN_ID', 'DEPEN_DESC')
                            ->where('DEPEN_STATUS','1')
                            ->where('DEPEN_ID',$depen_id)
                            ->get();                                        
            $regprogeanual= regProgeAnualModel::join('SIEVAD_CAT_DEPENDENCIAS','SIEVAD_CAT_DEPENDENCIAS.DEPEN_ID','=',
                                                                               'SIEVAD_EPA.DEPEN_ID')
                            ->select( 'SIEVAD_CAT_DEPENDENCIAS.DEPEN_DESC','SIEVAD_EPA.*')
                            ->where(  'SIEVAD_EPA.DEPEN_ID'  ,$depen_id)
                            ->orderBy('SIEVAD_EPA.PERIODO_ID','ASC')                          
                            ->orderBy('SIEVAD_EPA.DEPEN_ID'  ,'ASC')
                            ->orderBy('SIEVAD_EPA.FOLIO'     ,'ASC')                          
                            ->name($name)      //Metodos personalizados es equvalente a ->where('DEPEN_DESC', 'LIKE', "%$name%");
                            ->nameiap($nameiap) //Metodos personalizados                                  
                            //->email($email)   //Metodos personalizados
                            //->bio($bio)       //Metodos personalizados
                            ->paginate(50);        
        }                                                                                      
        if($regprogeanual->count() <= 0){
            toastr()->error('No existen registros del programa anual.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }            
        return view('sicinar.informelabores.verSoportes',compact('nombre','usuario','regdepen','reganios','regperiodos','regmeses','regdias','regprogeanual','totactivs'));
    }

    public function actionVerSoportes(){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $ip           = session()->get('ip');
        $arbol_id     = session()->get('arbol_id');        
        $depen_id     = session()->get('depen_id');     

        $regperiodos  = regPeriodosModel::select('PERIODO_ID', 'PERIODO_DESC')
                        ->get();         
        $regmeses     = regMesesModel::select('MES_ID','MES_DESC')
                        ->get();      
        $regdias      = regDiasModel::select('DIA_ID','DIA_DESC')
                        ->get();  
        $regepprog    = regEPprogramaModel::select('EPPROG_ID','EPPROG_DESC')
                        ->orderBy('EPPROG_ID','asc')
                        ->get();                         
        $regepproy    = regEPproyectoModel::select('EPPROy_ID','EPPROy_DESC')
                        ->orderBy('EPPROy_ID','asc')
                        ->get();                                                                         
        $regprogdanual= regProgdAnualModel::select('FOLIO','PARTIDA','PERIODO_ID','CIPREP_ID','LGOB_COD',
                        'DEPEN_ID1','DEPEN_ID2','DEPEN_ID3','EPPROG_ID','EPPROY_ID',
                        'FECHA_ENTREGA','FECHA_ENTREGA2','FECHA_ENTREGA3','PERIODO_ID1','MES_ID1','DIA_ID1','MES_ID2',
                        'PROGRAMA_ID','PROGRAMA_DESC','ACTIVIDAD_ID','ACTIVIDAD_DESC','OBJETIVO_ID','OBJETIVO_DESC',
                        'OPERACIONAL_DESC','UMED_ID','mesc_01','mesc_02','mesc_03','mesc_04','mesc_05','mesc_06',
                        'mesc_07','mesc_08','mesc_09','mesc_10','mesc_11','mesc_12',
                        'MESC_01','MESC_02','MESC_03','MESC_04','MESC_05','MESC_06',
                        'MESC_07','MESC_08','MESC_09','MESC_10','MESC_11','MESC_12',
                        'TRIMP_01','TRIMP_02','TRIMP_03','TRIMP_04','TOTP_01','TOTP_02',
                        'MES_P01','MES_P02','MES_P03','MES_P04','MES_P05','MES_P06',
                        'MES_P07','MES_P08','MES_P09','MES_P10','MES_P11','MES_P12',
                        'TRIM_P01','TRIM_P02','TRIM_P03','TRIM_P04','SEM_P01','SEM_P02','TOT_P01',
                        'SEMAF_01','SEMAF_02','SEMAF_03','SEMAF_04','SEMAF_05','SEMAF_06',
                        'SEMAF_07','SEMAF_08','SEMAF_09','SEMAF_10','SEMAF_11','SEMAF_12',
                        'SEMAFT_01','SEMAFT_02','SEMAFT_03','SEMAFT_04','SEMAFS_01','SEMAFS_02','SEMAFA_01',                        
                        'SOPORTE_ID','SOPORTE_01','SOPORTE_02','SOPORTE_03','SOPORTE_04','OBS_01','OBS_02',
                        'STATUS_1','STATUS_2','FECREG','FECREG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')        
                        ->orderBy('PERIODO_ID','asc')
                        ->orderBy('FOLIO'     ,'asc')
                        ->orderBy('PARTIDA'   ,'asc')
                        ->get();            
        //********* Validar rol de usuario **********************/
        if(session()->get('rango') !== '0'){  
            $regdepen     =regdepenModel::select('DEPEN_ID', 'DEPEN_DESC')
                           ->where('DEPEN_STATUS','1')
                           ->orderBy('DEPEN_ID'    ,'ASC')
                           ->get();   
            $totactivs    =regProgeAnualModel::join('SIEVAD_DPA','SIEVAD_DPA.FOLIO', '=','SIEVAD_EPA.FOLIO')
                           ->select(   'SIEVAD_EPA.PERIODO_ID','SIEVAD_EPA.FOLIO')
                           ->selectRaw('COUNT(*) AS TOTACTIVIDADES')
                           ->groupBy(  'SIEVAD_EPA.PERIODO_ID','SIEVAD_EPA.FOLIO')
                           ->get();                   
            $regprogeanual=regProgeAnualModel::select('FOLIO','PERIODO_ID','DEPEN_ID1','DEPEN_ID2','DEPEN_ID3',
                           'EPPROG_ID','EPPROY_ID','FECHA_ENTREGA','FECHA_ENTREGA2','FECHA_ENTREGA3',
                           'PERIODO_ID1','MES_ID1','DIA_ID1','MES_ID2','PROGRAMA_ID','PROGRAMA_DESC',
                           'RESPONSABLE','ELABORO','AUTORIZO','OBS_1','OBS_2','STATUS_1','STATUS_2',
                           'FECREG','FECREG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')
                           ->orderBy('PERIODO_ID','ASC')
                           ->orderBy('DEPEN_ID1' ,'ASC')
                           ->orderBy('FOLIO'     ,'ASC')
                           ->paginate(50);
        }else{                     
            $regdepen     =regdepenModel::select('DEPEN_ID', 'DEPEN_DESC')
                           ->where('DEPEN_STATUS','1')
                           ->where('DEPEN_ID',$depen_id)
                           ->get();                            
            $totactivs    =regProgeAnualModel::join('SIEVAD_DPA','SIEVAD_DPA.FOLIO','=','SIEVAD_EPA.FOLIO')
                           ->select(   'SIEVAD_EPA.PERIODO_ID','SIEVAD_EPA.FOLIO')
                           ->selectRaw('COUNT(*) AS TOTACTIVIDADES')
                           ->where(    'SIEVAD_EPA.DEPEN_ID',$depen_id) 
                           ->groupBy(  'SIEVAD_EPA.PERIODO_ID','SIEVAD_EPA.FOLIO')
                           ->get();                               
            $regprogeanual=regProgeAnualModel::select('FOLIO','PERIODO_ID','DEPEN_ID1','DEPEN_ID2','DEPEN_ID3',
                           'EPPROG_ID','EPPROY_ID','FECHA_ENTREGA','FECHA_ENTREGA2','FECHA_ENTREGA3',
                           'PERIODO_ID1','MES_ID1','DIA_ID1','MES_ID2','PROGRAMA_ID','PROGRAMA_DESC',
                           'RESPONSABLE','ELABORO','AUTORIZO','OBS_1','OBS_2','STATUS_1','STATUS_2',
                           'FECREG','FECREG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')
                           ->where(  'DEPEN_ID1' ,$depen_id)            
                           ->orderBy('PERIODO_ID','ASC')
                           ->orderBy('DEPEN_ID1' ,'ASC')
                           ->orderBy('FOLIO'     ,'ASC')  
                           ->paginate(50);          
        }                        
        if($regprogeanual->count() <= 0){
            toastr()->error('No existe Programa anual.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }
        return view('sicinar.informelabores.verSoportes',compact('nombre','usuario','regdepen','reganios','regperiodos','regmeses','regdias','regprogeanual','regprogdanual','totactivs','regepprog','regepproy')); 
    }    

    public function actionNuevoProgtrab(){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $ip           = session()->get('ip');
        $arbol_id     = session()->get('arbol_id');

        //$regumedida   = regUmedidaModel::select('UMEDIDA_ID','UMEDIDA_DESC')->orderBy('UMEDIDA_ID','asc')
        //                ->get();  
        $reganios     = regPeriodosaniosModel::select('PERIODO_ID','PERIODO_DESC')
                        ->wherein('PERIODO_ID',[2021,2022,2023])        
                        ->get();
        $regperiodos  = regPfiscalesModel::select('PERIODO_ID', 'PERIODO_DESC')->get();         
        $regmeses     = regMesesModel::select('MES_ID','MES_DESC')->get();      
        $regdias      = regDiasModel::select('DIA_ID','DIA_DESC')->get();     
        if(session()->get('rango') !== '0'){                           
            $regosc   = regOscModel::select('OSC_ID', 'OSC_DESC','OSC_STATUS')->orderBy('OSC_DESC','ASC')
                        ->get();                                                        
        }else{
            $regosc   = regOscModel::select('OSC_ID', 'OSC_DESC','OSC_STATUS')->orderBy('OSC_DESC','ASC')
                        ->where('OSC_ID',$arbol_id)
                        ->get();            
        }    
        $regprogtrab  = regProgtrabModel::select('FOLIO','PERIODO_ID','OSC_ID','FECHA_ELAB','FECHA_ELAB2',
                        'PERIODO_ID1','MES_ID1','DIA_ID1','PROGRAMA_ID','PROGRAMA_DESC',
                        'RESPONSABLE','ELABORO','AUTORIZO','OBS_1','OBS_2','STATUS_1','STATUS_2',
                        'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->orderBy('OSC_ID','asc')
                        ->get();
        //dd($unidades);
        return view('sicinar.programatrabajo.nuevoProgtrab',compact('regosc','nombre','usuario','reganios','regperiodos','regmeses','regdias','regprogtrab'));                         
    }

    public function actionAltaNuevoProgtrab(Request $request){
        //dd($request->all());
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $ip           = session()->get('ip');
        $arbol_id     = session()->get('arbol_id');

        /************ Obtenemos la IP ***************************/                
        if (getenv('HTTP_CLIENT_IP')) {
            $ip = getenv('HTTP_CLIENT_IP');
        } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
            $ip = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('HTTP_X_FORWARDED')) {
            $ip = getenv('HTTP_X_FORWARDED');
        } elseif (getenv('HTTP_FORWARDED_FOR')) {
            $ip = getenv('HTTP_FORWARDED_FOR');
        } elseif (getenv('HTTP_FORWARDED')) {
            $ip = getenv('HTTP_FORWARDED');
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }        

        // *************** Validar duplicidad ***********************************/
        $duplicado = regProgtrabModel::where(['PERIODO_ID' => $request->periodo_id,'OSC_ID' => $request->osc_id])
                     ->get();
        if($duplicado->count() >= 1)
            return back()->withInput()->withErrors(['OSC_ID' => 'OSC '.$request->osc_id.' Ya existe programa de trabajo en el mismo periodo y con la IAP referida. Por favor verificar.']);
        else{  
            /************ ALTA  *****************************/ 
            if(!empty($request->periodo_d1) and !empty($request->mes_d1) and !empty($request->dia_d1) ){
                //toastr()->error('muy bien 1.....','¡ok!',['positionClass' => 'toast-bottom-right']);
                $mes1 = regMesesModel::ObtMes($request->mes_id1);
                $dia1 = regDiasModel::ObtDia($request->dia_id1);                
            }   //endif

            $mes1 = regMesesModel::ObtMes($request->mes_id1);
            $dia1 = regDiasModel::ObtDia($request->dia_id1);                

            $folio = regProgtrabModel::max('FOLIO');
            $folio = $folio + 1;

            $nuevoprogtrab = new regProgtrabModel();
            $nuevoprogtrab->FOLIO         = $folio;
            $nuevoprogtrab->PERIODO_ID    = $request->periodo_id;                            
            $nuevoprogtrab->OSC_ID        = $request->osc_id;
            $nuevoprogtrab->FECHA_ELAB    = date('Y/m/d', strtotime(trim($dia1[0]->dia_desc.'/'.$mes1[0]->mes_mes.'/'.$request->periodo_id1) ));
            $nuevoprogtrab->FECHA_ELAB2   = trim($dia1[0]->dia_desc.'/'.$mes1[0]->mes_mes.'/'.$request->periodo_id1);
            $nuevoprogtrab->PERIODO_ID1   = $request->periodo_id1;                
            $nuevoprogtrab->MES_ID1       = $request->mes_id1;                
            $nuevoprogtrab->DIA_ID1       = $request->dia_id1;       

            $nuevoprogtrab->RESPONSABLE   = substr(trim(strtoupper($request->responsable)),0, 99);
            $nuevoprogtrab->ELABORO       = substr(trim(strtoupper($request->elaboro))    ,0, 99);
            $nuevoprogtrab->AUTORIZO      = substr(trim(strtoupper($request->autorizo))   ,0, 99);
            $nuevoprogtrab->OBS_1         = substr(trim(strtoupper($request->obs_1))      ,0,299);
        
            $nuevoprogtrab->IP            = $ip;
            $nuevoprogtrab->LOGIN         = $nombre;         // Usuario ;
            $nuevoprogtrab->save();
            if($nuevoprogtrab->save() == true){
                toastr()->success('Programa de trabajo dado de alta.','ok!',['positionClass' => 'toast-bottom-right']);
                /************ Bitacora inicia *************************************/ 
                setlocale(LC_TIME, "spanish");        
                $xip          = session()->get('ip');
                $xperiodo_id  = (int)date('Y');
                $xprograma_id = 1;
                $xmes_id      = (int)date('m');
                $xproceso_id  =         3;
                $xfuncion_id  =      3011;
                $xtrx_id      =        12;    //Alta
                $regbitacora = regBitacoraModel::select('PERIODO_ID','MES_ID','PROCESO_ID','FUNCION_ID', 
                                                    'TRX_ID','FOLIO','NO_VECES','FECHA_REG','IP','LOGIN', 
                                                    'FECHA_M','IP_M','LOGIN_M')
                               ->where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id,
                                    'PROCESO_ID' => $xproceso_id,'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id, 
                                    'FOLIO' => $folio])
                               ->get();
                if($regbitacora->count() <= 0){              // Alta
                    $nuevoregBitacora = new regBitacoraModel();              
                    $nuevoregBitacora->PERIODO_ID = $xperiodo_id;    // Año de transaccion 
                    
                    $nuevoregBitacora->MES_ID     = $xmes_id;        // Mes de transaccion
                    $nuevoregBitacora->PROCESO_ID = $xproceso_id;    // Proceso de apoyo
                    $nuevoregBitacora->FUNCION_ID = $xfuncion_id;    // Funcion del modelado de procesos 
                    $nuevoregBitacora->TRX_ID     = $xtrx_id;        // Actividad del modelado de procesos
                    $nuevoregBitacora->FOLIO      = $folio;          // Folio    
                    $nuevoregBitacora->NO_VECES   = 1;               // Numero de veces            
                    $nuevoregBitacora->IP         = $ip;             // IP
                    $nuevoregBitacora->LOGIN      = $nombre;         // Usuario 
                    $nuevoregBitacora->save();
                    if($nuevoregBitacora->save() == true)
                        toastr()->success('Bitacora dada de alta correctamente.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                    else
                        toastr()->error('Error inesperado al dar de alta la bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
                }else{                   
                    //*********** Obtine el no. de veces *****************************
                    $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id, 
                                                          'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id,'FOLIO'      => $folio])
                                 ->max('NO_VECES');
                    $xno_veces = $xno_veces+1;                        
                    //*********** Termina de obtener el no de veces *****************************         
                    $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                                   ->where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id, 
                                            'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id,'FOLIO'      => $folio])
                                   ->update([
                                         'NO_VECES'=> $regbitacora->NO_VECES = $xno_veces,
                                         'IP_M'    => $regbitacora->IP       = $ip,
                                         'LOGIN_M' => $regbitacora->LOGIN_M  = $nombre,
                                         'FECHA_M' => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                       ]);
                    toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                }   /************ Bitacora termina *************************************/ 

            }else{
                toastr()->error('Error al dar de alta el programa de trabajo. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
                //return back();
                //return redirect()->route('nuevoProceso');
            }   //**************** Termina la alta ***************/
        }   // ******************* Termina el duplicado **********/

        return redirect()->route('verProgtrab');
    }

    
    public function actionEditarProgtrab($id){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango');
        $arbol_id      = session()->get('arbol_id');        

        //$regumedida   = regUmedidaModel::select('UMEDIDA_ID','UMEDIDA_DESC')->orderBy('UMEDIDA_ID','asc')
        //                ->get();   
        $reganios     = regPeriodosaniosModel::select('PERIODO_ID','PERIODO_DESC')
                        ->wherein('PERIODO_ID',[2021,2022,2023])        
                        ->get();
        $regperiodos  = regPfiscalesModel::select('PERIODO_ID', 'PERIODO_DESC')->get();         
        $regmeses     = regMesesModel::select('MES_ID','MES_DESC')->get();      
        $regdias      = regDiasModel::select('DIA_ID','DIA_DESC')->get();         
        //********* Validar rol de usuario **********************/
        if(session()->get('rango') !== '0'){                          
            $regosc       = regOscModel::select('OSC_ID', 'OSC_DESC','OSC_STATUS')
                            ->get();                                                        
        }else{
            $regosc   = regOscModel::select('OSC_ID', 'OSC_DESC','OSC_STATUS')
                        ->where('OSC_ID',$arbol_id)
                        ->get();            
        }                    
        $regprogtrab  = regProgtrabModel::select('FOLIO','PERIODO_ID','OSC_ID','FECHA_ELAB','FECHA_ELAB2',
                        'PERIODO_ID1','MES_ID1','DIA_ID1','PROGRAMA_ID','PROGRAMA_DESC',
                        'RESPONSABLE','ELABORO','AUTORIZO','OBS_1','OBS_2','STATUS_1','STATUS_2',
                        'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where('FOLIO',$id)
                        ->orderBy('PERIODO_ID','ASC')
                        ->orderBy('OSC_ID','ASC')
                        ->first();
        if($regprogtrab->count() <= 0){
            toastr()->error('No existen registros de programas de trabajo.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevoprogtrab');
        }
        //return view('sicinar.programatrabajo.editarProgtrab',compact('nombre','usuario','regosc','regumedida','reganios','regperiodos','regmeses','regdias','regprogtrab'));
        return view('sicinar.programatrabajo.editarProgtrab',compact('nombre','usuario','regosc','reganios','regperiodos','regmeses','regdias','regprogtrab'));

    }

    public function actionActualizarProgtrab(progtrabRequest $request, $id){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango');
        $ip            = session()->get('ip');
        $arbol_id      = session()->get('arbol_id');        

        // **************** actualizar ******************************
        $regprogtrab = regProgtrabModel::where('FOLIO',$id);
        if($regprogtrab->count() <= 0)
            toastr()->error('No existe programa de trabajo.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            //********************** Actualizar ********************************/
            //xiap_feccons =null;
            if(!empty($request->periodo_d1) and !empty($request->mes_d1) and !empty($request->dia_d1) ){
                //toastr()->error('muy bien 1....................','¡ok...........!',['positionClass' => 'toast-bottom-right']);
                $mes1 = regMesesModel::ObtMes($request->mes_id1);
                $dia1 = regDiasModel::ObtDia($request->dia_id1);                
                //xiap_feccons = $dia1[0]->dia_desc.'/'.$mes1[0]->mes_mes.'/'.$request->periodo_id1;
            }   //endif
            $mes1 = regMesesModel::ObtMes($request->mes_id1);
            $dia1 = regDiasModel::ObtDia($request->dia_id1);                
            //dd('año 1:',$request->periodo_id1, ' año 2:',$request->periodo_id2,' mes1:',$mes1[0]->mes_mes,' dia1:',$dia1[0]->dia_desc,' mes2:',$mes2[0]->mes_mes, ' dia2:',$dia2[0]->dia_desc);
            $regprogtrab = regProgtrabModel::where('FOLIO',$id)        
                           ->update([                
                'PROGRAMA_DESC' => substr(trim(strtoupper($request->programa_desc)),0,499),
                //'ACTIVIDAD_DESC'=> substr(trim(strtoupper($request->actividad_desc)),0,499),
                //'OBJETIVO_DESC' => substr(trim(strtoupper($request->objetivo_desc)),0,499),
                //'UMEDIDA_ID'    => $request->umedida_id,

                'FECHA_ELAB'    => date('Y/m/d', strtotime(trim($dia1[0]->dia_desc.'/'.$mes1[0]->mes_mes.'/'.$request->periodo_id1) )),
                'FECHA_ELAB2'   => trim($dia1[0]->dia_desc.'/'.$mes1[0]->mes_mes.'/'.$request->periodo_id1), 
                'PERIODO_ID1'   => $request->periodo_id1,
                'MES_ID1'       => $request->mes_id1,
                'DIA_ID1'       => $request->dia_id1,                

                'ELABORO'       => substr(trim(strtoupper($request->elaboro)),0,99),
                'RESPONSABLE'   => substr(trim(strtoupper($request->responsable)),0,99),
                'AUTORIZO'      => substr(trim(strtoupper($request->autorizo)),0,99),
                'OBS_1'         => substr(trim(strtoupper($request->obs_1)),0,299),        
                'STATUS_1'      => $request->status_1,

                'IP_M'          => $ip,
                'LOGIN_M'       => $nombre,
                'FECHA_M'       => date('Y/m/d')    //date('d/m/Y')                                
                                   ]);
            toastr()->success('Programa de trabajo actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3011;
            $xtrx_id      =        13;    //Actualizar 
            $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID', 'FUNCION_ID', 
                           'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                           ->where(['PERIODO_ID' => $xperiodo_id,  'MES_ID' => $xmes_id, 
                                    'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 
                                    'FOLIO' => $id])
                           ->get();
            if($regbitacora->count() <= 0){              // Alta
                $nuevoregBitacora = new regBitacoraModel();              
                $nuevoregBitacora->PERIODO_ID = $xperiodo_id;    // Año de transaccion 
                $nuevoregBitacora->PROGRAMA_ID= $xprograma_id;   // Proyecto JAPEM 
                $nuevoregBitacora->MES_ID     = $xmes_id;        // Mes de transaccion
                $nuevoregBitacora->PROCESO_ID = $xproceso_id;    // Proceso de apoyo
                $nuevoregBitacora->FUNCION_ID = $xfuncion_id;    // Funcion del modelado de procesos 
                $nuevoregBitacora->TRX_ID     = $xtrx_id;        // Actividad del modelado de procesos
                $nuevoregBitacora->FOLIO      = $id;             // Folio    
                $nuevoregBitacora->NO_VECES   = 1;               // Numero de veces            
                $nuevoregBitacora->IP         = $ip;             // IP
                $nuevoregBitacora->LOGIN      = $nombre;         // Usuario 
                $nuevoregBitacora->save();
                if($nuevoregBitacora->save() == true)
                    toastr()->success('Bitacora dada de alta correctamente.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                else
                    toastr()->error('Error inesperado al dar de alta la bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
            }else{                   
                //*********** Obtine el no. de veces *****************************
                $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id,  
                             'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 
                             'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                             ->max('NO_VECES');
                $xno_veces = $xno_veces+1;                        
                //*********** Termina de obtener el no de veces *****************************         
                $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                               ->where(['PERIODO_ID' => $xperiodo_id,  
                                        'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 
                                        'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                               ->update([
                                         'NO_VECES'=> $regbitacora->NO_VECES = $xno_veces,
                                         'IP_M'    => $regbitacora->IP       = $ip,
                                         'LOGIN_M' => $regbitacora->LOGIN_M  = $nombre,
                                         'FECHA_M' => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                         ]);
                toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/                     
        }       /************ Actualizar *******************************************/

        return redirect()->route('verProgtrab');

    }



    //*****************************************************************************//
    //*************************************** Detalle *****************************//
    //*****************************************************************************//
    public function actionVerSoportesdet($id){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $ip           = session()->get('ip');
        $arbol_id     = session()->get('arbol_id');        
        $depen_id     = session()->get('depen_id');            

        $regumedida   = regUmedidaModel::select('UMED_ID','UMED_DESC')
                        ->orderBy('UMED_ID','asc')
                        ->get();      
        $regperiodos  = regPeriodosModel::select('PERIODO_ID', 'PERIODO_DESC')
                        ->get();         
        $reganios     = regAniosModel::select('ANIO_ID','ANIO_DESC')
                        ->get();        
        $regmeses     = regMesesModel::select('MES_ID','MES_DESC')
                        ->get();      
        $regdias      = regDiasModel::select('DIA_ID','DIA_DESC')
                        ->get();                                
        $regdepen     = regdepenModel::select('DEPEN_ID', 'DEPEN_DESC')
                        ->where('DEPEN_STATUS','1')
                        ->orderBy('DEPEN_ID','asc')
                        ->get();      
        $regepprog    = regEPprogramaModel::select('EPPROG_ID','EPPROG_DESC')
                        ->orderBy('EPPROG_ID','asc')
                        ->get();                         
        $regepproy    = regEPproyectoModel::select('EPPROY_ID','EPPROY_DESC')
                        ->orderBy('EPPROY_ID','asc')
                        ->get();                                                                                                                     
        //************** Validar rol de usuario **********************/
        if(session()->get('rango') !== '0'){           
            $regprogeanual=regProgeAnualModel::select('FOLIO','PERIODO_ID','DEPEN_ID1','DEPEN_ID2','DEPEN_ID3',
                           'EPPROG_ID','EPPROY_ID','FECHA_ENTREGA','FECHA_ENTREGA2','FECHA_ENTREGA3',
                           'PERIODO_ID1','MES_ID1','DIA_ID1','MES_ID2','PROGRAMA_ID','PROGRAMA_DESC',
                           'RESPONSABLE','ELABORO','AUTORIZO','OBS_1','OBS_2','STATUS_1','STATUS_2',
                           'FECREG','FECREG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')
                           ->where(  'FOLIO'     ,$id)
                           ->orderBy('PERIODO_ID','ASC')
                           ->orderBy('DEPEN_ID1' ,'ASC')
                           ->get();
        }else{                         
            $regprogeanual=regProgeAnualModel::select('FOLIO','PERIODO_ID','DEPEN_ID1','DEPEN_ID2','DEPEN_ID3',
                           'EPPROG_ID','EPPROY_ID','FECHA_ENTREGA','FECHA_ENTREGA2','FECHA_ENTREGA3',
                           'PERIODO_ID1','MES_ID1','DIA_ID1','MES_ID2','PROGRAMA_ID','PROGRAMA_DESC',
                           'RESPONSABLE','ELABORO','AUTORIZO','OBS_1','OBS_2','STATUS_1','STATUS_2',
                           'FECREG','FECREG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')
                           ->where(['FOLIO' => $id, 'DEPEN_ID1' => $depen_id])
                           ->orderBy('PERIODO_ID','ASC')
                           ->orderBy('DEPEN_ID1' ,'ASC')
                           ->get();
        }                        
        $regprogdanual= regProgdAnualModel::select('FOLIO','PARTIDA','PERIODO_ID','CIPREP_ID','LGOB_COD',
                        'DEPEN_ID1','DEPEN_ID2','DEPEN_ID3','EPPROG_ID','EPPROY_ID',
                        'FECHA_ENTREGA','FECHA_ENTREGA2','FECHA_ENTREGA3','PERIODO_ID1','MES_ID1','DIA_ID1','MES_ID2',
                        'PROGRAMA_ID','PROGRAMA_DESC','ACTIVIDAD_ID','ACTIVIDAD_DESC','OBJETIVO_ID','OBJETIVO_DESC',
                        'OPERACIONAL_DESC','UMED_ID','MESP_01','MESP_02','MESP_03','MESP_04','MESP_05','MESP_06',
                        'MESP_07','MESP_08','MESP_09','MESP_10','MESP_11','MESP_12',
                        'MESC_01','MESC_02','MESC_03','MESC_04','MESC_05','MESC_06',
                        'MESC_07','MESC_08','MESC_09','MESC_10','MESC_11','MESC_12',
                        'TRIMP_01','TRIMP_02','TRIMP_03','TRIMP_04','TOTP_01','TOTP_02',
                        'TRIMC_01','TRIMC_02','TRIMC_03','TRIMC_04','TOTC_01','TOTC_02',
                        'TSEMP_01','TSEMP_02','TSEMC_01','TSEMC_02',        
                        'MES_P01','MES_P02','MES_P03','MES_P04','MES_P05','MES_P06',
                        'MES_P07','MES_P08','MES_P09','MES_P10','MES_P11','MES_P12',
                        'TRIM_P01','TRIM_P02','TRIM_P03','TRIM_P04','SEM_P01','SEM_P02','TOT_P01',
                        'SEMAF_01','SEMAF_02','SEMAF_03','SEMAF_04','SEMAF_05','SEMAF_06',
                        'SEMAF_07','SEMAF_08','SEMAF_09','SEMAF_10','SEMAF_11','SEMAF_12',
                        'SEMAFT_01','SEMAFT_02','SEMAFT_03','SEMAFT_04','SEMAFS_01','SEMAFS_02','SEMAFA_01', 
                        'SOPORTE_ID','SOPORTE_01','SOPORTE_02','SOPORTE_03','SOPORTE_04','SOPORTE_05','SOPORTE_06',
                        'SOPORTE_07','SOPORTE_08','SOPORTE_09','SOPORTE_10','SOPORTE_11','SOPORTE_12',
                        'OBS_01','OBS_02',
                        'STATUS_1','STATUS_2','FECREG','FECREG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')        
                        ->where(  'FOLIO'  ,$id)            
                        ->orderBy('FOLIO'  ,'asc')
                        ->orderBy('PARTIDA','asc')
                        ->paginate(50);           
        if($regprogdanual->count() <= 0){
            toastr()->error('No existen acciones o metas del Programa anual.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }                        
        return view('sicinar.informelabores.verSoportesdet',compact('nombre','usuario','regdepen','regumedida','reganios','regperiodos','regmeses','regdias','regprogeanual','regprogdanual','regepprog','regepproy'));
    }


    public function actionNuevoProgdtrab($id){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $ip           = session()->get('ip');
        $arbol_id     = session()->get('arbol_id');

        $regumedida   = regUmedidaModel::select('UMEDIDA_ID','UMEDIDA_DESC')->orderBy('UMEDIDA_ID','asc')
                        ->get();  
        //$regperiodos  = regPfiscalesModel::select('PERIODO_ID', 'PERIODO_DESC')->get();         
        //$reganios     = regPeriodosaniosModel::select('PERIODO_ID','PERIODO_DESC')
        //                ->wherein('PERIODO_ID',[2021,2022,2023])        
        //                ->get();        
        //$regmeses     = regMesesModel::select('MES_ID','MES_DESC')->get();      
        //$regdias      = regDiasModel::select('DIA_ID','DIA_DESC')->get();     
        if(session()->get('rango') !== '0'){                           
            $regosc   = regOscModel::select('OSC_ID', 'OSC_DESC','OSC_STATUS')->orderBy('OSC_DESC','ASC')
                        ->get();                                                        
        }else{
            $regosc   = regOscModel::select('OSC_ID', 'OSC_DESC','OSC_STATUS')->orderBy('OSC_DESC','ASC')
                        ->where('OSC_ID',$arbol_id)
                        ->get();            
        }    
        $regprogtrab  = regProgtrabModel::select('FOLIO','PERIODO_ID','OSC_ID','FECHA_ELAB','FECHA_ELAB2',
                        'PERIODO_ID1','MES_ID1','DIA_ID1','PROGRAMA_ID','PROGRAMA_DESC',
                        'RESPONSABLE','ELABORO','AUTORIZO','OBS_1','OBS_2','STATUS_1','STATUS_2',
                        'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where('FOLIO',$id)            
                        ->get();
        $regprogdtrab = regProgdtrabModel::select('FOLIO','PARTIDA','PERIODO_ID','OSC_ID','DFECHA_ELAB',
                        'DFECHA_ELAB2','PROGRAMA_ID','PROGRAMA_DESC','ACTIVIDAD_ID',
                        'ACTIVIDAD_DESC','OBJETIVO_ID','OBJETIVO_DESC','UMEDIDA_ID',
                        'mesc_01','mesc_02','mesc_03','mesc_04','mesc_05','mesc_06',
                        'mesc_07','mesc_08','mesc_09','mesc_10','mesc_11','mesc_12',
                        'MESC_01','MESC_02','MESC_03','MESC_04','MESC_05','MESC_06',
                        'MESC_07','MESC_08','MESC_09','MESC_10','MESC_11','MESC_12',
                        'DOBS_1','DOBS_2','DSTATUS_1','DSTATUS_2',
                        'FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')        
                        ->where('FOLIO',$id)                    
                        ->orderBy('FOLIO','asc')
                        ->orderBy('PARTIDA','asc')
                        ->get();                                
        //dd($unidades);
        //return view('sicinar.programatrabajo.nuevoProgtrab',compact('regumedida','regosc','nombre','usuario','reganios','regperiodos','regmeses','regdias','regprogtrab'));
        return view('sicinar.programatrabajo.nuevoProgdtrab',compact('nombre','usuario','regosc','regumedida','regprogtrab','regprogdtrab'));   
    }

    public function actionAltaNuevoProgdtrab(Request $request){
        //dd($request->all());
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $ip           = session()->get('ip');
        $arbol_id     = session()->get('arbol_id');

        /************ Obtenemos la IP ***************************/                
        if (getenv('HTTP_CLIENT_IP')) {
            $ip = getenv('HTTP_CLIENT_IP');
        } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
            $ip = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('HTTP_X_FORWARDED')) {
            $ip = getenv('HTTP_X_FORWARDED');
        } elseif (getenv('HTTP_FORWARDED_FOR')) {
            $ip = getenv('HTTP_FORWARDED_FOR');
        } elseif (getenv('HTTP_FORWARDED')) {
            $ip = getenv('HTTP_FORWARDED');
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }        

        // *************** Validar duplicidad ***********************************/
        //$duplicado = regProgtrabModel::where(['PERIODO_ID' => $request->periodo_id,'OSC_ID' => $request->osc_id])
        //             ->get();
        //if($duplicado->count() >= 1)
        //    return back()->withInput()->withErrors(['OSC_ID' => 'IAP '.$request->osc_id.' Ya existe programa de trabajo en el mismo periodo y con la IAP referida. Por favor verificar.']);
        //else{  

            /************ ALTA  *****************************/ 
            $mes1 = regMesesModel::ObtMes($request->mes_id1);
            $dia1 = regDiasModel::ObtDia($request->dia_id1);                
            // ******** Obtiene partida ************************/
            $partida = regProgdtrabModel::where(['PERIODO_ID'=> $request->periodo_id, 
                                                 'OSC_ID'    => $request->osc_id, 
                                                 'FOLIO'     => $request->folio])
                       ->max('PARTIDA');
            $partida = $partida + 1;

            $nuevoprogdtrab = new regProgdtrabModel();
            $nuevoprogdtrab->FOLIO         = $request->folio;
            $nuevoprogdtrab->PARTIDA       = $partida;
            $nuevoprogdtrab->PERIODO_ID    = $request->periodo_id;                            
            $nuevoprogdtrab->OSC_ID        = $request->osc_id;
            $nuevoprogdtrab->DFECHA_ELAB   = date('Y/m/d', strtotime(trim($dia1[0]->dia_desc.'/'.$mes1[0]->mes_mes.'/'.$request->periodo_id1) ));
            $nuevoprogdtrab->DFECHA_ELAB2  = trim($dia1[0]->dia_desc.'/'.$mes1[0]->mes_mes.'/'.$request->periodo_id1);

            $nuevoprogdtrab->PROGRAMA_DESC = substr(trim(strtoupper($request->programa_desc)),0,499);
            $nuevoprogdtrab->ACTIVIDAD_DESC= substr(trim(strtoupper($request->actividad_desc)),0,499);
            $nuevoprogdtrab->OBJETIVO_DESC = substr(trim(strtoupper($request->objetivo_desc)),0,499);
            $nuevoprogdtrab->UMEDIDA_ID    = $request->umedida_id;
            $nuevoprogdtrab->mesc_01       = $request->mesc_01;
            $nuevoprogdtrab->mesc_02       = $request->mesc_02;
            $nuevoprogdtrab->mesc_03       = $request->mesc_03;
            $nuevoprogdtrab->mesc_04       = $request->mesc_04;
            $nuevoprogdtrab->mesc_05       = $request->mesc_05;
            $nuevoprogdtrab->mesc_06       = $request->mesc_06;
            $nuevoprogdtrab->mesc_07       = $request->mesc_07;
            $nuevoprogdtrab->mesc_08       = $request->mesc_08;
            $nuevoprogdtrab->mesc_09       = $request->mesc_09;
            $nuevoprogdtrab->mesc_10       = $request->mesc_10;
            $nuevoprogdtrab->mesc_11       = $request->mesc_11;
            $nuevoprogdtrab->mesc_12       = $request->mesc_12;
            $nuevoprogdtrab->DOBS_1        = substr(trim(strtoupper($request->dobs_1)),0,299);
      
            $nuevoprogdtrab->IP            = $ip;
            $nuevoprogdtrab->LOGIN         = $nombre;         // Usuario ;
            $nuevoprogdtrab->save();
            if($nuevoprogdtrab->save() == true){
                toastr()->success('Actividad del programa de trabajo dada de alta.','ok!',['positionClass' => 'toast-bottom-right']);
                /************ Bitacora inicia *************************************/ 
                setlocale(LC_TIME, "spanish");        
                $xip          = session()->get('ip');
                $xperiodo_id  = (int)date('Y');
                $xprograma_id = 1;
                $xmes_id      = (int)date('m');
                $xproceso_id  =         3;
                $xfuncion_id  =      3011;
                $xtrx_id      =        12;    //Alta
                $regbitacora = regBitacoraModel::select('PERIODO_ID','PROGRAMA_ID','MES_ID','PROCESO_ID','FUNCION_ID', 
                                                    'TRX_ID','FOLIO','NO_VECES','FECHA_REG','IP','LOGIN', 
                                                    'FECHA_M','IP_M','LOGIN_M')
                               ->where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id,
                                    'PROCESO_ID' => $xproceso_id,'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id, 
                                    'FOLIO' => $request->folio])
                               ->get();
                if($regbitacora->count() <= 0){              // Alta
                    $nuevoregBitacora = new regBitacoraModel();              
                    $nuevoregBitacora->PERIODO_ID = $xperiodo_id;    // Año de transaccion 
                    $nuevoregBitacora->PROGRAMA_ID= $xprograma_id;   // Proyecto JAPEM 
                    $nuevoregBitacora->MES_ID     = $xmes_id;        // Mes de transaccion
                    $nuevoregBitacora->PROCESO_ID = $xproceso_id;    // Proceso de apoyo
                    $nuevoregBitacora->FUNCION_ID = $xfuncion_id;    // Funcion del modelado de procesos 
                    $nuevoregBitacora->TRX_ID     = $xtrx_id;        // Actividad del modelado de procesos
                    $nuevoregBitacora->FOLIO      = $request->folio;          // Folio    
                    $nuevoregBitacora->NO_VECES   = 1;               // Numero de veces            
                    $nuevoregBitacora->IP         = $ip;             // IP
                    $nuevoregBitacora->LOGIN      = $nombre;         // Usuario 
                    $nuevoregBitacora->save();
                    if($nuevoregBitacora->save() == true)
                        toastr()->success('Bitacora dada de alta correctamente.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                    else
                        toastr()->error('Error inesperado al dar de alta la bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
                }else{                   
                    //*********** Obtine el no. de veces *****************************
                    $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id,  
                                                      'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                                      'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 
                                                      'FOLIO' => $request->folio])
                                 ->max('NO_VECES');
                    $xno_veces = $xno_veces+1;                        
                    //*********** Termina de obtener el no de veces *****************************         
                    $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                                   ->where(['PERIODO_ID' => $xperiodo_id,  
                                        'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                        'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id,'FOLIO' => $request->folio])
                                   ->update([
                                         'NO_VECES'=> $regbitacora->NO_VECES = $xno_veces,
                                         'IP_M'    => $regbitacora->IP       = $ip,
                                         'LOGIN_M' => $regbitacora->LOGIN_M  = $nombre,
                                         'FECHA_M' => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                       ]);
                    toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                }   /************ Bitacora termina *************************************/ 

            }else{
                toastr()->error('Error al dar de alta el programa de trabajo. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
                //return back();
                //return redirect()->route('nuevoProceso');
            }   //**************** Termina la alta ***************/
        //}   // ******************* Termina el duplicado **********/

        return redirect()->route('verProgtrab');
    }

    public function actionEditarInformelab($id,$id2){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $arbol_id     = session()->get('arbol_id');        
        $depen_id     = session()->get('depen_id');                

        $regumedida   = regUmedidaModel::select('UMED_ID','UMED_DESC')
                        ->orderBy('UMED_ID','asc')
                        ->get();      
        $regperiodos  = regPeriodosModel::select('PERIODO_ID', 'PERIODO_DESC')
                        ->get();         
        $reganios     = regAniosModel::select('ANIO_ID','ANIO_DESC')
                        ->get();        
        $regmeses     = regMesesModel::select('MES_ID','MES_DESC')
                        ->get();      
        $regdias      = regDiasModel::select('DIA_ID','DIA_DESC')
                        ->get();                                
        $regepprog    = regEPprogramaModel::select('EPPROG_ID','EPPROG_DESC')
                        ->orderBy('EPPROG_ID','asc')
                        ->get();                         
        $regepproy    = regEPproyectoModel::select('EPPROY_ID','EPPROY_DESC')
                        ->orderBy('EPPROY_ID','asc')
                        ->get();                
        //********* Validar rol de usuario **********************/
        if(session()->get('rango') !== '0'){                          
            $regdepen = regdepenModel::select('DEPEN_ID', 'DEPEN_DESC')
                        ->where('DEPEN_STATUS','1')
                        ->get();                                                         
        }else{
            $regdepen = regdepenModel::select('DEPEN_ID', 'DEPEN_DESC')
                        ->where('DEPEN_STATUS','1')
                        ->where('DEPEN_ID',$depen_id)
                        ->get();               
        }                    
        $regprogeanual= regProgeAnualModel::select('FOLIO','PERIODO_ID','DEPEN_ID1','DEPEN_ID2','DEPEN_ID3',
                        'EPPROG_ID','EPPROY_ID','FECHA_ENTREGA','FECHA_ENTREGA2','FECHA_ENTREGA3',
                        'PERIODO_ID1','MES_ID1','DIA_ID1','MES_ID2','PROGRAMA_ID','PROGRAMA_DESC',
                        'RESPONSABLE','ELABORO','AUTORIZO','OBS_1','OBS_2','STATUS_1','STATUS_2',
                        'FECREG','FECREG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')
                        ->where(  'FOLIO'     ,$id)
                        ->orderBy('PERIODO_ID','ASC')
                        ->orderBy('FOLIO'     ,'ASC')
                        ->get();
        $regprogdanual= regProgdAnualModel::select('FOLIO','PARTIDA','PERIODO_ID','CIPREP_ID','LGOB_COD',
                        'DEPEN_ID1','DEPEN_ID2','DEPEN_ID3','EPPROG_ID','EPPROY_ID',
                        'FECHA_ENTREGA','FECHA_ENTREGA2','FECHA_ENTREGA3','PERIODO_ID1','MES_ID1','DIA_ID1','MES_ID2',
                        'PROGRAMA_ID','PROGRAMA_DESC','ACTIVIDAD_ID','ACTIVIDAD_DESC','OBJETIVO_ID','OBJETIVO_DESC',
                        'OPERACIONAL_DESC','UMED_ID','MESP_01','MESP_02','MESP_03','MESP_04','MESP_05','MESP_06',
                        'MESP_07','MESP_08','MESP_09','MESP_10','MESP_11','MESP_12',
                        'MESC_01','MESC_02','MESC_03','MESC_04','MESC_05','MESC_06',
                        'MESC_07','MESC_08','MESC_09','MESC_10','MESC_11','MESC_12',
                        'TRIMP_01','TRIMP_02','TRIMP_03','TRIMP_04','TOTP_01','TOTP_02',
                        'TRIMC_01','TRIMC_02','TRIMC_03','TRIMC_04','TOTC_01','TOTC_02',
                        'TSEMP_01','TSEMP_02','TSEMC_01','TSEMC_02',       
                        'MES_P01','MES_P02','MES_P03','MES_P04','MES_P05','MES_P06',
                        'MES_P07','MES_P08','MES_P09','MES_P10','MES_P11','MES_P12',
                        'TRIM_P01','TRIM_P02','TRIM_P03','TRIM_P04','SEM_P01','SEM_P02','TOT_P01',
                        'SEMAF_01','SEMAF_02','SEMAF_03','SEMAF_04','SEMAF_05','SEMAF_06',
                        'SEMAF_07','SEMAF_08','SEMAF_09','SEMAF_10','SEMAF_11','SEMAF_12',
                        'SEMAFT_01','SEMAFT_02','SEMAFT_03','SEMAFT_04','SEMAFS_01','SEMAFS_02','SEMAFA_01', 
                        'SOPORTE_ID','SOPORTE_01','SOPORTE_02','SOPORTE_03','SOPORTE_04','SOPORTE_05','SOPORTE_06',
                        'SOPORTE_07','SOPORTE_08','SOPORTE_09','SOPORTE_10','SOPORTE_11','SOPORTE_12',
                        'OBS_01','OBS_02',
                        'STATUS_1','STATUS_2','FECREG','FECREG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')        
                        ->where(['FOLIO' => $id, 'PARTIDA' => $id2])
                        //->where('FOLIO',$id)
                        //->where('PARTIDA',$id2)
                        ->first();  
        if($regprogdanual->count() <= 0){
            toastr()->error('No existen registros de acciones o metas del Programa anual.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }
        return view('sicinar.informelabores.editarInformelab',compact('nombre','usuario','regdepen','reganios','regperiodos','regmeses','regdias','regprogeanual','regprogdanual','regumedida','regepproy','regepprog'));
    }

    public function actionActualizarInformelab(informelabRequest $request, $id, $id2){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango');
        $ip            = session()->get('ip');
        $arbol_id      = session()->get('arbol_id');    
        $depen_id      = session()->get('depen_id');                    

        // **************** actualizar ******************************
        $regprogdanual= regProgdAnualModel::where(['FOLIO' => $id, 'PARTIDA' => $id2]);
        if($regprogdanual->count() <= 0)
            toastr()->error('No existe acción o meta del programa anual.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            //************ Obtiene valores programados y cumplidos **********//
            $mesesp  = regProgdAnualModel::obtvalores($id,$id2);       
            //************ Valores programados ******************************//
            $mesp_01  = $mesesp[0]->mesp_01;    
            $mesp_02  = $mesesp[0]->mesp_02;    
            $mesp_03  = $mesesp[0]->mesp_03;    
            $mesp_04  = $mesesp[0]->mesp_04;    
            $mesp_05  = $mesesp[0]->mesp_05;    
            $mesp_06  = $mesesp[0]->mesp_06;    
            $mesp_07  = $mesesp[0]->mesp_07;    
            $mesp_08  = $mesesp[0]->mesp_08;    
            $mesp_09  = $mesesp[0]->mesp_09;    
            $mesp_10  = $mesesp[0]->mesp_10;    
            $mesp_11  = $mesesp[0]->mesp_11;    
            $mesp_12  = $mesesp[0]->mesp_12;  
 
            $trimp_01 = $mesesp[0]->trimp_01;    
            $trimp_02 = $mesesp[0]->trimp_02;    
            $trimp_03 = $mesesp[0]->trimp_03;    
            $trimp_04 = $mesesp[0]->trimp_04;

            $tsemp_01 = $mesesp[0]->tsemp_01;    
            $tsemp_02 = $mesesp[0]->tsemp_02;    

            $totp_01  = $mesesp[0]->totp_01;    
            $totp_02  = $mesesp[0]->totp_02;    
            
            //************ Valores cumplidos *******************************//
            $mesc_01  = $mesesp[0]->mesc_01;    
            $mesc_02  = $mesesp[0]->mesc_02;    
            $mesc_03  = $mesesp[0]->mesc_03;    
            $mesc_04  = $mesesp[0]->mesc_04;    
            $mesc_05  = $mesesp[0]->mesc_05;    
            $mesc_06  = $mesesp[0]->mesc_06;    
            $mesc_07  = $mesesp[0]->mesc_07;    
            $mesc_08  = $mesesp[0]->mesc_08;    
            $mesc_09  = $mesesp[0]->mesc_09;    
            $mesc_10  = $mesesp[0]->mesc_10;    
            $mesc_11  = $mesesp[0]->mesc_11;    
            $mesc_12  = $mesesp[0]->mesc_12;  
 
            $trimc_01 = $mesesp[0]->trimc_01;    
            $trimc_02 = $mesesp[0]->trimc_02;    
            $trimc_03 = $mesesp[0]->trimc_03;    
            $trimc_04 = $mesesp[0]->trimc_04;

            $tsemc_01 = $mesesp[0]->tsemc_01;    
            $tsemc_02 = $mesesp[0]->tsemc_02;    

            $totc_01  = $mesesp[0]->totc_01;    
            $totc_02  = $mesesp[0]->totc_02;

            //************ Validar avance cumplido mensual **********************//
            $avance = 0;
            if(isset($request->avance)){
                if(!empty($request->avance)) 
                    $avance = (float)$request->avance;
            }          
            
            //************ En función del mes se actualiza la variable ***********//
            switch ((int)$request->mes_id2) 
            {
            case 1:    //mes enero
                  $mesc_01 = $avance;
                  break;                                
            case 2:    //mes feb
                  $mesc_02 = $avance;
                  break;  
            case 3:    //mes mar
                  $mesc_03 = $avance;
                  break;  
            case 4:    //mes abr
                  $mesc_04 = $avance;
                  break;  
            case 5:    //mes may
                  $mesc_05 = $avance;
                  break;  
            case 6:    //mes jun
                  $mesc_06 = $avance;
                  break;  
            case 7:    //mes jul
                  $mesc_07 = $avance;
                  break;  
            case 8:    //mes ago
                  $mesc_08 = $avance;
                  break;  
            case 9:    //mes sep
                  $mesc_09 = $avance;
                  break;  
            case 10:    //mes oct
                  $mesc_10 = $avance;
                  break;  
            case 11:    //mes nov
                  $mesc_11 = $avance;
                  break;        
            case 12:    //mes dic
                  $mesc_12 = $avance;
                  break;                                                                                                                                                                                                  
            }   // fin clase              

            //*************** Calcular los 4 trimestres ***********************//            
            $trimc_01 = (float)$mesc_01 + (float)$mesc_02 + (float)$mesc_03;
            $trimc_02 = (float)$mesc_04 + (float)$mesc_05 + (float)$mesc_06;
            $trimc_03 = (float)$mesc_07 + (float)$mesc_08 + (float)$mesc_09;
            $trimc_04 = (float)$mesc_10 + (float)$mesc_11 + (float)$mesc_12;
            //*************** Calcular los 2 semestres ***********************//            
            $tsemc_01 = (float)$trimc_01 + (float)$trimc_02;
            $tsemc_02 = (float)$trimc_03 + (float)$trimc_04;            
            //*************** Calcular cumplimiento anual ********************//            
            $totc_01  = (float)$tsemc_01 + (float)$tsemc_02;          
            $totc_02  = (float)$tsemc_01 + (float)$tsemc_02;

            //*************** Calcular porcentajes mensuales *****************//
            $mes_p01  = 0;
            if ((float)$mesc_01 > 0 && (float)$mesp_01 > 0)
               $mes_p01  = ($mesc_01/$mesp_01)*100;    
            $mes_p02  = 0;
            if ((float)$mesc_02 > 0 && (float)$mesp_02 > 0)
               $mes_p02  = ($mesc_02/$mesp_02)*100;   
            $mes_p03  = 0;
            if ((float)$mesc_03 > 0 && (float)$mesp_03 > 0)
               $mes_p03  = ($mesc_03/$mesp_03)*100;   
            $mes_p04  = 0;
            if ((float)$mesc_04 > 0 && (float)$mesp_04 > 0)
               $mes_p04  = ($mesc_04/$mesp_04)*100;   
            $mes_p05  = 0;
            if ((float)$mesc_05 > 0 && (float)$mesp_05 > 0)
               $mes_p05  = ($mesc_05/$mesp_05)*100;   
            $mes_p06 = 0;
            if ((float)$mesc_06 > 0 && (float)$mesp_06 > 0)
               $mes_p06  = ($mesc_06/$mesp_06)*100;   
            $mes_p07  = 0;
            if ((float)$mesc_07 > 0 && (float)$mesp_07 > 0)
               $mes_p07  = ($mesc_07/$mesp_07)*100;   
            $mes_p08  = 0;
            if ((float)$mesc_08 > 0 && (float)$mesp_08 > 0)
               $mes_p08  = ($mesc_08/$mesp_08)*100;   
            $mes_p09  = 0;
            if ((float)$mesc_09 > 0 && (float)$mesp_09 > 0)
               $mes_p09  = ($mesc_09/$mesp_09)*100;   
            $mes_p10  = 0;
            if ((float)$mesc_10 > 0 && (float)$mesp_10 > 0)
               $mes_p10  = ($mesc_10/$mesp_10)*100;   
            $mes_p11  = 0;
            if ((float)$mesc_11 > 0 && (float)$mesp_11 > 0)
               $mes_p11  = ($mesc_11/$mesp_11)*100;   
            //dd('mes 12 programado:'.$mesp_12,'avance:'.$mesc_12);
            $mes_p12  = 0;
            if ((float)$mesc_12 > 0 && (float)$mesp_12 > 0)
               $mes_p12  = ($mesc_12/$mesp_12)*100;   

           //********************** Calcular porcentajes trimestrales **********************/
            $trim_p01  = 0;
            if ((float)$trimc_01 > 0 && (float)$trimp_01 > 0)
               $trim_p01  = ($trimc_01/$trimp_01)*100;    
            $trim_p02  = 0;
            if ((float)$trimc_02 > 0 && (float)$trimp_02 > 0)
               $trim_p02  = ($trimc_02/$trimp_02)*100;   
            $trim_p03  = 0;
            if ((float)$trimc_03 > 0 && (float)$trimp_03 > 0)
               $trim_p03  = ($trimc_03/$trimp_03)*100;   
            $trim_p04  = 0;
            if ((float)$trimc_04 > 0 && (float)$trimp_04 > 0)
               $trim_p04  = ($trimc_04/$trimp_04)*100;  

           //********************** Calcular porcentajes semestrales **********************/
            $sem_p01  = 0;
            if ((float)$tsemc_01 > 0 && (float)$tsemp_01 > 0)
               $sem_p01  = ($tsemc_01/$tsemp_01)*100;    
            $sem_p02  = 0;
            if ((float)$tsemc_02 > 0 && (float)$tsemp_02 > 0)
               $sem_p02  = ($tsemc_02/$tsemp_02)*100;  

           //********************** Calcular porcentaje anual **********************/
            $tot_p01  = 0;
            if ((float)$totc_01 > 0 && (float)$totp_01 > 0)
               $tot_p01  = ($totc_01/$totp_01)*100;   
            $tot_p02  = 0;
            if ((float)$totc_02 > 0 && (float)$totp_02 > 0)
               $tot_p02  = ($totc_02/$totp_02)*100;              

            //********************** Obtener semáforos **************************//
            //RANGOS DE CALIFICACIÓN  
            //5 Avance deficiente (Intervalo 110.1 - 300  %)  Mora Azul
            //4 Avance excelente (Intervalo 90 - 110 %)   Verde 
            //3 Avance regular (Intervalo 70 - 89.9 %)  Amarillo
            //2 Avance pesimo (Intervalo (50 - 69.9%) Naranja
            //1 Situacion critica  (Intervalo 0 - 49.9 %) Rojo
            //******************************************************************//
            $semaf_01  = 0;
            if ((float)$mes_p01 > 0 && (float)$mes_p01 < 50){
                 $semaf_01 = 1;
            }else{
                if ((float)$mes_p01 > 50 && (float)$mes_p01 < 70){
                    $semaf_01 = 2;
                }else{
                    if ((float)$mes_p01 > 70 && (float)$mes_p01 < 90){
                        $semaf_01 = 3;
                    }else{
                        if ((float)$mes_p01 > 90 && (float)$mes_p01 <= 110){
                            $semaf_01 = 4;
                        }else{
                          if ((float)$mes_p01 > 110 && (float)$mes_p01 <= 300)
                             $semaf_01 = 5;
                        }
                    }
                }
            }
            $semaf_02  = 0;
            if ((float)$mes_p02 > 0 && (float)$mes_p02 < 50){
                 $semaf_02 = 1;
            }else{
                if ((float)$mes_p02 > 50 && (float)$mes_p02 < 70){
                    $semaf_02 = 2;
                }else{
                    if ((float)$mes_p02 > 70 && (float)$mes_p02 < 90){
                        $semaf_02 = 3;
                    }else{
                        if ((float)$mes_p02 > 90 && (float)$mes_p02 <= 110){
                            $semaf_02 = 4;
                        }else{
                          if ((float)$mes_p02 > 110 && (float)$mes_p02 <= 300)
                             $semaf_02 = 5;
                        }
                    }
                }
            }
            $semaf_03  = 0;
            if ((float)$mes_p03 > 0 && (float)$mes_p03 < 50){
                 $semaf_03 = 1;
            }else{
                if ((float)$mes_p03 > 50 && (float)$mes_p03 < 70){
                    $semaf_03 = 2;
                }else{
                    if ((float)$mes_p03 > 70 && (float)$mes_p03 < 90){
                        $semaf_03 = 3;
                    }else{
                        if ((float)$mes_p03 > 90 && (float)$mes_p03 <= 110){
                            $semaf_03 = 4;
                        }else{
                          if ((float)$mes_p03 > 110 && (float)$mes_p03 <= 300)
                             $semaf_03 = 5;
                        }
                    }
                }
            }
            $semaf_04  = 0;
            if ((float)$mes_p04 > 0 && (float)$mes_p04 < 50){
                 $semaf_04 = 1;
            }else{
                if ((float)$mes_p04 > 50 && (float)$mes_p04 < 70){
                    $semaf_04 = 2;
                }else{
                    if ((float)$mes_p04 > 70 && (float)$mes_p04 < 90){
                       $semaf_04 = 3;
                    }else{
                        if ((float)$mes_p04 > 90 && (float)$mes_p04 <= 110){
                            $semaf_04 = 4;
                        }else{
                          if ((float)$mes_p04 > 110 && (float)$mes_p04 <= 300)
                             $semaf_04 = 5;
                        }
                    }
                }
            }
            $semaf_05  = 0;
            if ((float)$mes_p05 > 0 && (float)$mes_p05 < 50){
                 $semaf_05 = 1;
            }else{
                if ((float)$mes_p05 > 50 && (float)$mes_p05 < 70){
                    $semaf_05 = 2;
                }else{
                    if ((float)$mes_p05 > 70 && (float)$mes_p05 < 90){
                        $semaf_05 = 3;
                    }else{
                        if ((float)$mes_p05 > 90 && (float)$mes_p05 <= 110){
                            $semaf_05 = 4;
                        }else{
                          if ((float)$mes_p05 > 110 && (float)$mes_p05 <= 300)
                             $semaf_05 = 5;
                        }
                    }
                }
            }
            $semaf_06  = 0;
            if ((float)$mes_p06 > 0 && (float)$mes_p06 < 50){
                 $semaf_06 = 1;
            }else{
                if ((float)$mes_p06 > 50 && (float)$mes_p06 < 70){
                    $semaf_06 = 2;
                }else{
                    if ((float)$mes_p06 > 70 && (float)$mes_p06 < 90){
                        $semaf_06 = 3;
                    }else{
                        if ((float)$mes_p06 > 90 && (float)$mes_p06 <= 110){
                            $semaf_06 = 4;
                        }else{
                          if ((float)$mes_p06 > 110 && (float)$mes_p06 <= 300)
                             $semaf_06 = 5;
                        }
                    }
                }
            }
            $semaf_07  = 0;
            if ((float)$mes_p07 > 0 && (float)$mes_p07 < 50){
                 $semaf_07 = 1;
            }else{
                if ((float)$mes_p07 > 50 && (float)$mes_p07 < 70){
                    $semaf_07 = 2;
                }else{
                    if ((float)$mes_p07 > 70 && (float)$mes_p07 < 90){
                        $semaf_07 = 3;
                    }else{
                        if ((float)$mes_p07 > 90 && (float)$mes_p07 <= 110){
                            $semaf_07 = 4;
                        }else{
                          if ((float)$mes_p07 > 110 && (float)$mes_p07 <= 300)
                             $semaf_07 = 5;
                        }
                    }
                }
            }
            $semaf_08  = 0;
            if ((float)$mes_p08 > 0 && (float)$mes_p08 < 50){
                 $semaf_08 = 1;
            }else{
                if ((float)$mes_p08 > 50 && (float)$mes_p08 < 70){
                    $semaf_08 = 2;
                }else{
                    if ((float)$mes_p08 > 70 && (float)$mes_p08 < 90){
                        $semaf_08 = 3;
                    }else{
                        if ((float)$mes_p08 > 90 && (float)$mes_p08 <= 110){
                            $semaf_08 = 4;
                        }else{
                          if ((float)$mes_p08 > 110 && (float)$mes_p08 <= 300)
                             $semaf_08 = 5;
                        }
                    }
                }
            }
            $semaf_09  = 0;
            if ((float)$mes_p09 > 0 && (float)$mes_p09 < 50){
                 $semaf_09 = 1;
            }else{
                if ((float)$mes_p09 > 50 && (float)$mes_p09 < 70){
                    $semaf_09 = 2;
                }else{
                    if ((float)$mes_p09 > 70 && (float)$mes_p09 < 90){
                        $semaf_09 = 3;
                    }else{
                        if ((float)$mes_p09 > 90 && (float)$mes_p09 <= 110){
                            $semaf_09 = 4;
                        }else{
                          if ((float)$mes_p09 > 110 && (float)$mes_p09 <= 300)
                             $semaf_09 = 5;
                        }
                    }
                }
            }
            $semaf_10  = 0;
            if ((float)$mes_p10 > 0 && (float)$mes_p10 < 50){
                 $semaf_10 = 1;
            }else{
                if ((float)$mes_p10 > 50 && (float)$mes_p10 < 70){
                    $semaf_10 = 2;
                }else{
                    if ((float)$mes_p10 > 70 && (float)$mes_p10 < 90){
                        $semaf_10 = 3;
                    }else{
                        if ((float)$mes_p10 > 90 && (float)$mes_p10 <= 110){
                            $semaf_10 = 4;
                        }else{
                          if ((float)$mes_p10 > 110 && (float)$mes_p10 <= 300)
                             $semaf_10 = 5;
                        }
                    }
                }
            }
            $semaf_11  = 0;
            if ((float)$mes_p11 > 0 && (float)$mes_p11 < 50){
                 $semaf_11 = 1;
            }else{
                if ((float)$mes_p11 > 50 && (float)$mes_p11 < 70){
                    $semaf_11 = 2;
                }else{
                    if ((float)$mes_p11 > 70 && (float)$mes_p11 < 90){
                        $semaf_11 = 3;
                    }else{
                        if ((float)$mes_p11 > 90 && (float)$mes_p11 <= 110){
                            $semaf_11 = 4;
                        }else{
                          if ((float)$mes_p11 > 110 && (float)$mes_p11 <= 300)
                             $semaf_11 = 5;
                        }
                    }
                }
            }
            $semaf_12  = 0;
            if ((float)$mes_p12 > 0 && (float)$mes_p12 < 50){
                 $semaf_12 = 1;
            }else{
                if ((float)$mes_p12 > 50 && (float)$mes_p12 < 70){
                    $semaf_12 = 2;
                }else{
                    if ((float)$mes_p12 > 70 && (float)$mes_p12 < 90){
                        $semaf_12 = 3;
                    }else{
                        if ((float)$mes_p12 > 90 && (float)$mes_p12 <= 110){
                            $semaf_12 = 4;
                        }else{
                            if ((float)$mes_p12 > 110 && (float)$mes_p12 <= 300)
                               $semaf_12 = 5;
                        }
                    }
                }
            }

            //*************** obtiene semaforos trimestrales **********************
            $semaft_01  = 0;
            if ((float)$trim_p01 > 0 && (float)$trim_p01 < 50){
                 $semaft_01 = 1;
            }else{
                if ((float)$trim_p01 > 50 && (float)$trim_p01 < 70){
                    $semaft_01 = 2;
                }else{
                    if ((float)$trim_p01 > 70 && (float)$trim_p01 < 90){
                        $semaft_01 = 3;
                    }else{
                        if ((float)$trim_p01 > 90 && (float)$trim_p01 <= 110){
                            $semaft_01 = 4;
                        }else{
                            if ((float)$trim_p01 > 110 && (float)$trim_p01 <= 300)
                               $semaft_01 = 5;
                        }
                    }
                }
            }
            $semaft_02  = 0;
            if ((float)$trim_p02 > 0 && (float)$trim_p02 < 50){
                 $semaft_02 = 1;
            }else{
                if ((float)$trim_p02 > 50 && (float)$trim_p02 < 70){
                    $semaft_02 = 2;
                }else{
                    if ((float)$trim_p02 > 70 && (float)$trim_p02 < 90){
                        $semaft_02 = 3;
                    }else{
                        if ((float)$trim_p02 > 90 && (float)$trim_p02 <= 110){
                            $semaft_02 = 4;
                        }else{
                            if ((float)$trim_p02 > 110 && (float)$trim_p02 <= 300)
                               $semaft_02 = 5;
                        }
                    }
                }
            }
            $semaft_03  = 0;
            if ((float)$trim_p03 > 0 && (float)$trim_p03 < 50){
                 $semaft_03 = 1;
            }else{
                if ((float)$trim_p03 > 50 && (float)$trim_p03 < 70){
                    $semaft_03 = 2;
                }else{
                    if ((float)$trim_p03 > 70 && (float)$trim_p03 < 90){
                        $semaft_03 = 3;
                    }else{
                        if ((float)$trim_p03 > 90 && (float)$trim_p03 <= 110){
                            $semaft_03 = 4;
                        }else{
                            if ((float)$trim_p03 > 110 && (float)$trim_p03 <= 300)
                               $semaft_03 = 5;
                        }
                    }
                }
            }
            $semaft_04  = 0;
            if ((float)$trim_p04 > 0 && (float)$trim_p04 < 50){
                 $semaft_04 = 1;
            }else{
                if ((float)$trim_p04 > 50 && (float)$trim_p04 < 70){
                    $semaft_04 = 2;
                }else{
                    if ((float)$trim_p04 > 70 && (float)$trim_p04 < 90){
                        $semaft_04 = 3;
                    }else{
                        if ((float)$trim_p04 > 90 && (float)$trim_p04 <= 110){
                            $semaft_04 = 4;
                        }else{
                          if ((float)$trim_p04 > 110 && (float)$trim_p04 <= 300)
                             $semaft_04 = 5;
                        }
                    }
                }
            }
            //*************** semaforos semestrales *********************
            $semafs_01  = 0;
            if ((float)$sem_p01 > 0 && (float)$sem_p01 < 50){
                 $semafs_01 = 1;
            }else{
                if ((float)$sem_p01 > 50 && (float)$sem_p01 < 70){
                    $semafs_01 = 2;
                }else{
                    if ((float)$sem_p01 > 70 && (float)$sem_p01 < 90){
                        $semafs_01 = 3;
                    }else{
                        if ((float)$sem_p01 > 90 && (float)$sem_p01 <= 110){
                            $semafs_01 = 4;
                        }else{
                          if ((float)$sem_p01 > 110 && (float)$sem_p01 <= 300)
                             $semafs_01 = 5;
                        }
                    }
                }
            }
            $semafs_02  = 0;
            if ((float)$sem_p02 > 0 && (float)$sem_p02 < 50){
                 $semafs_02 = 1;
            }else{
                if ((float)$sem_p02 > 50 && (float)$sem_p02 < 70){
                    $semafs_02 = 2;
                }else{
                    if ((float)$sem_p02 > 70 && (float)$sem_p02 < 90){
                        $semafs_02 = 3;
                    }else{
                        if ((float)$sem_p02 > 90 && (float)$sem_p02 <= 110){
                            $semafs_02 = 4;
                        }else{
                            if ((float)$sem_p02 > 110 && (float)$sem_p02 <= 300)
                               $semafs_02 = 5;
                        }
                    }
                }
            }

           //********************** Calcular semaforo anual **********************/
            $semafa_01  = 0;
            if ((float)$tot_p01 > 0 && (float)$tot_p01 < 50){
                 $semafa_01 = 1;
            }else{
                if ((float)$tot_p01 > 50 && (float)$tot_p01 < 70){
                    $semafa_01 = 2;
                }else{
                    if ((float)$tot_p01 > 70 && (float)$tot_p01 < 90){
                        $semafa_01 = 3;
                    }else{
                        if ((float)$tot_p01 > 90 && (float)$tot_p01 <= 110){
                            $semafa_01 = 4;
                        }else{
                          if ((float)$tot_p01 > 110 && (float)$tot_p01 <= 300)
                             $semafa_01 = 5;
                        }
                    }
                }
            }

            //***************************** Actualizar ********************************//
            //dd('mes:',$request->mes_id2);
            switch ((int)$request->mes_id2) 
            {
            case 1:    //mes enero
                $regprogdanual= regProgdAnualModel::where(['FOLIO' => $id, 'PARTIDA' => $id2])        
                            ->update([                
                                      'MES_ID2'   => $request->mes_id2,
                                      'MESC_01'   => $request->avance,
                                      'TRIMC_01'  => $trimc_01,
                                      'TSEMC_01'  => $tsemc_01,
                                      'TOTC_01'   => $totc_01,
                                      'TOTC_02'   => $totc_02,
                                      'MES_P01'   => $mes_p01,
                                      'TRIM_P01'  => $trim_p01,
                                      'SEM_P01'   => $sem_p01,
                                      'TOT_P01'   => $tot_p01,
                                      'SEMAF_01'  => $semaf_01,
                                      'SEMAFT_01' => $semaft_01,
                                      'SEMAFS_01' => $semafs_01,
                                      'SEMAFA_01' => $semafa_01,
                                      'OBS_02'    => substr(trim(strtoupper($request->obs_02)),0,3999),        

                                      'IP_M'      => $ip,
                                      'LOGIN_M'   => $nombre,
                                      'FECHA_M2'  => date('Y/m/d'),   //date('d/m/Y')            
                                      'FECHA_M'   => date('Y/m/d')    //date('d/m/Y')                                
                                      ]);
                toastr()->success('Avance del programa anual actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                break;
            case 2:    //Mes febrero
                $regprogdanual= regProgdAnualModel::where(['FOLIO' => $id, 'PARTIDA' => $id2])        
                            ->update([                
                                      'MES_ID2'   => $request->mes_id2,
                                      'MESC_02'   => $request->avance,
                                      'TRIMC_01'  => $trimc_01,
                                      'TSEMC_01'  => $tsemc_01,
                                      'TOTC_01'   => $totc_01,
                                      'TOTC_02'   => $totc_02,
                                      'MES_P02'   => $mes_p02,
                                      'TRIM_P01'  => $trim_p01,
                                      'SEM_P01'   => $sem_p01,
                                      'TOT_P01'   => $tot_p01,
                                      'SEMAF_02'  => $semaf_02,
                                      'SEMAFT_01' => $semaft_01,
                                      'SEMAFS_01' => $semafs_01,
                                      'SEMAFA_01' => $semafa_01,
                                      'OBS_02'    => substr(trim(strtoupper($request->obs_02)),0,3999),        

                                      'IP_M'      => $ip,
                                      'LOGIN_M'   => $nombre,
                                      'FECHA_M2'  => date('Y/m/d'),   //date('d/m/Y')            
                                      'FECHA_M'   => date('Y/m/d')    //date('d/m/Y')                                
                                      ]);
                toastr()->success('Avance del programa anual actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);            
                break;
            case 3:    //Mes marzo
                $regprogdanual= regProgdAnualModel::where(['FOLIO' => $id, 'PARTIDA' => $id2])        
                            ->update([                
                                      'MES_ID2'   => $request->mes_id2,
                                      'MESC_03'   => $request->avance,
                                      'TRIMC_01'  => $trimc_01,
                                      'TSEMC_01'  => $tsemc_01,
                                      'TOTC_01'   => $totc_01,
                                      'TOTC_02'   => $totc_02,
                                      'MES_P03'   => $mes_p03,
                                      'TRIM_P01'  => $trim_p01,
                                      'SEM_P01'   => $sem_p01,
                                      'TOT_P01'   => $tot_p01,

                                      'SEMAF_03'  => $semaf_03,                                      
                                      'SEMAFT_01' => $semaft_01,
                                      'SEMAFS_01' => $semafs_01,
                                      'SEMAFA_01' => $semafa_01,
                                      'OBS_02'    => substr(trim(strtoupper($request->obs_02)),0,3999),        

                                      'IP_M'      => $ip,
                                      'LOGIN_M'   => $nombre,
                                      'FECHA_M2'  => date('Y/m/d'),   //date('d/m/Y')            
                                      'FECHA_M'   => date('Y/m/d')    //date('d/m/Y')                                
                                      ]);
                toastr()->success('Avance del programa anual actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);            
                break;
            case 4:    //Mes abril
                $regprogdanual= regProgdAnualModel::where(['FOLIO' => $id, 'PARTIDA' => $id2])        
                            ->update([                
                                      'MES_ID2'   => $request->mes_id2,
                                      'MESC_04'   => $request->avance,
                                      'TRIMC_02'  => $trimc_02,
                                      'TSEMC_01'  => $tsemc_01,
                                      'TOTC_01'   => $totc_01,
                                      'TOTC_02'   => $totc_02,
                                      'MES_P04'   => $mes_p04,
                                      'TRIM_P02'  => $trim_p02,
                                      'SEM_P01'   => $sem_p01,
                                      'TOT_P01'   => $tot_p01,

                                      'SEMAF_04'  => $semaf_04,
                                      'SEMAFT_02' => $semaft_02,
                                      'SEMAFS_01' => $semafs_01,
                                      'SEMAFA_01' => $semafa_01,
                                      'OBS_02'    => substr(trim(strtoupper($request->obs_02)),0,3999),        

                                      'IP_M'      => $ip,
                                      'LOGIN_M'   => $nombre,
                                      'FECHA_M2'  => date('Y/m/d'),   //date('d/m/Y')            
                                      'FECHA_M'   => date('Y/m/d')    //date('d/m/Y')                                
                                      ]);
                toastr()->success('Avance del programa anual actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);            
                break;
            case 5:    //Mes mayo
                $regprogdanual= regProgdAnualModel::where(['FOLIO' => $id, 'PARTIDA' => $id2])        
                            ->update([                
                                      'MES_ID2'   => $request->mes_id2,
                                      'MESC_05'   => $request->avance,
                                      'TRIMC_02'  => $trimc_02,
                                      'TSEMC_01'  => $tsemc_01,
                                      'TOTC_01'   => $totc_01,
                                      'TOTC_02'   => $totc_02,
                                      'MES_P05'   => $mes_p05,
                                      'TRIM_P02'  => $trim_p02,
                                      'SEM_P01'   => $sem_p01,
                                      'TOT_P01'   => $tot_p01,

                                      'SEMAF_05'  => $semaf_05,
                                      'SEMAFT_02' => $semaft_02,
                                      'SEMAFS_01' => $semafs_01,
                                      'SEMAFA_01' => $semafa_01,
                                      'OBS_02'    => substr(trim(strtoupper($request->obs_02)),0,3999),        

                                      'IP_M'      => $ip,
                                      'LOGIN_M'   => $nombre,
                                      'FECHA_M2'  => date('Y/m/d'),   //date('d/m/Y')            
                                      'FECHA_M'   => date('Y/m/d')    //date('d/m/Y')                                
                                      ]);
                toastr()->success('Avance del programa anual actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);            
                break;      
            case 6:    //Mes junio
                $regprogdanual= regProgdAnualModel::where(['FOLIO' => $id, 'PARTIDA' => $id2])        
                            ->update([                
                                      'MES_ID2'   => $request->mes_id2,
                                      'MESC_06'   => $request->avance,
                                      'TRIMC_02'  => $trimc_02,
                                      'TSEMC_01'  => $tsemc_01,
                                      'TOTC_01'   => $totc_01,
                                      'TOTC_02'   => $totc_02,
                                      'MES_P06'   => $mes_p06,
                                      'TRIM_P02'  => $trim_p02,
                                      'SEM_P01'   => $sem_p01,
                                      'TOT_P01'   => $tot_p01,

                                      'SEMAF_06'  => $semaf_06,                                      
                                      'SEMAFT_02' => $semaft_02,
                                      'SEMAFS_01' => $semafs_01,
                                      'SEMAFA_01' => $semafa_01,
                                      'OBS_02'    => substr(trim(strtoupper($request->obs_02)),0,3999),        

                                      'IP_M'      => $ip,
                                      'LOGIN_M'   => $nombre,
                                      'FECHA_M2'  => date('Y/m/d'),   //date('d/m/Y')            
                                      'FECHA_M'   => date('Y/m/d')    //date('d/m/Y')                                
                                      ]);
                toastr()->success('Avance del programa anual actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);             
                break;
            case 7:    //Mes julio
                $regprogdanual= regProgdAnualModel::where(['FOLIO' => $id, 'PARTIDA' => $id2])        
                            ->update([                
                                      'MES_ID2'   => $request->mes_id2,
                                      'MESC_07'   => $request->avance,
                                      'TRIMC_03'  => $trimc_03,
                                      'TSEMC_02'  => $tsemc_02,
                                      'TOTC_01'   => $totc_01,
                                      'TOTC_02'   => $totc_02,
                                      'MES_P07'   => $mes_p07,
                                      'TRIM_P03'  => $trim_p03,
                                      'SEM_P02'   => $sem_p01,
                                      'TOT_P01'   => $tot_p01,

                                      'SEMAF_07'  => $semaf_07,
                                      'SEMAFT_03' => $semaft_03,
                                      'SEMAFS_02' => $semafs_02,
                                      'SEMAFA_01' => $semafa_01,
                                      'OBS_02'    => substr(trim(strtoupper($request->obs_02)),0,3999),  

                                      'IP_M'      => $ip,
                                      'LOGIN_M'   => $nombre,
                                      'FECHA_M2'  => date('Y/m/d'),   //date('d/m/Y')            
                                      'FECHA_M'   => date('Y/m/d')    //date('d/m/Y')                                
                                      ]);
                toastr()->success('Avance del programa anual actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);            
                break;
            case 8:    //Mes agosto
                $regprogdanual= regProgdAnualModel::where(['FOLIO' => $id, 'PARTIDA' => $id2])        
                            ->update([                
                                      'MES_ID2'   => $request->mes_id2,
                                      'MESC_08'   => $request->avance,
                                      'TRIMC_03'  => $trimc_03,
                                      'TSEMC_02'  => $tsemc_02,
                                      'TOTC_01'   => $totc_01,
                                      'TOTC_02'   => $totc_02,
                                      'MES_P08'   => $mes_p08,
                                      'TRIM_P03'  => $trim_p03,
                                      'SEM_P02'   => $sem_p01,
                                      'TOT_P01'   => $tot_p01,

                                      'SEMAF_08'  => $semaf_08,
                                      'SEMAFT_03' => $semaft_03,
                                      'SEMAFS_02' => $semafs_02,
                                      'SEMAFA_01' => $semafa_01,
                                      'OBS_02'    => substr(trim(strtoupper($request->obs_02)),0,3999),        

                                      'IP_M'      => $ip,
                                      'LOGIN_M'   => $nombre,
                                      'FECHA_M2'  => date('Y/m/d'),   //date('d/m/Y')            
                                      'FECHA_M'   => date('Y/m/d')    //date('d/m/Y')                                
                                      ]);
                toastr()->success('Avance del programa anual actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);            
                break;
            case 9:    //Mes septiembre
                $regprogdanual= regProgdAnualModel::where(['FOLIO' => $id, 'PARTIDA' => $id2])        
                            ->update([                
                                      'MES_ID2'   => $request->mes_id2,
                                      'MESC_09'   => $request->avance,
                                      'TRIMC_03'  => $trimc_03,
                                      'TSEMC_02'  => $tsemc_02,
                                      'TOTC_01'   => $totc_01,
                                      'TOTC_02'   => $totc_02,
                                      'MES_P09'   => $mes_p09,
                                      'TRIM_P03'  => $trim_p03,
                                      'SEM_P02'   => $sem_p01,
                                      'TOT_P01'   => $tot_p01,

                                      'SEMAF_09'  => $semaf_09,
                                      'SEMAFT_03' => $semaft_03,
                                      'SEMAFS_02' => $semafs_02,
                                      'SEMAFA_01' => $semafa_01,
                                      'OBS_02'    => substr(trim(strtoupper($request->obs_02)),0,3999),        

                                      'IP_M'      => $ip,
                                      'LOGIN_M'   => $nombre,
                                      'FECHA_M2'  => date('Y/m/d'),   //date('d/m/Y')            
                                      'FECHA_M'   => date('Y/m/d')    //date('d/m/Y')                                
                                      ]);
                toastr()->success('Avance del programa anual actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);            
                break;
            case 10:    //Mes octubre
                $regprogdanual= regProgdAnualModel::where(['FOLIO' => $id, 'PARTIDA' => $id2])        
                                ->update([                
                                      'MES_ID2'   => $request->mes_id2,
                                      'MESC_10'   => $request->avance,
                                      'TRIMC_04'  => $trimc_04,
                                      'TSEMC_02'  => $tsemc_02,
                                      'TOTC_01'   => $totc_01,
                                      'TOTC_02'   => $totc_02,

                                      'MES_P10'   => $mes_p10,
                                      'TRIM_P04'  => $trim_p04,
                                      'SEM_P02'   => $sem_p01,
                                      'TOT_P01'   => $tot_p01,

                                      'SEMAF_10'  => $semaf_10,
                                      'SEMAFT_04' => $semaft_04,
                                      'SEMAFS_02' => $semafs_02,
                                      'SEMAFA_01' => $semafa_01,
                                      'OBS_02'    => substr(trim(strtoupper($request->obs_02)),0,3999),        

                                      'IP_M'      => $ip,
                                      'LOGIN_M'   => $nombre,
                                      'FECHA_M2'  => date('Y/m/d'),   //date('d/m/Y')            
                                      'FECHA_M'   => date('Y/m/d')    //date('d/m/Y')                                
                                      ]);
                toastr()->success('Avance del programa anual actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);            
                break; 
            case 11:    //Mes noviembre
                $regprogdanual= regProgdAnualModel::where(['FOLIO' => $id, 'PARTIDA' => $id2])        
                            ->update([                
                                      'MES_ID2'   => $request->mes_id2,
                                      'MESC_11'   => $request->avance,
                                      'TRIMC_04'  => $trimc_04,
                                      'TSEMC_02'  => $tsemc_02,
                                      'TOTC_01'   => $totc_01,
                                      'TOTC_02'   => $totc_02,

                                      'MES_P11'   => $mes_p11,
                                      'TRIM_P04'  => $trim_p04,
                                      'SEM_P02'   => $sem_p01,
                                      'TOT_P01'   => $tot_p01,

                                      'SEMAF_11'  => $semaf_11,
                                      'SEMAFT_04' => $semaft_04,
                                      'SEMAFS_02' => $semafs_02,
                                      'SEMAFA_01' => $semafa_01,
                                      'OBS_02'    => substr(trim(strtoupper($request->obs_02)),0,3999),        

                                      'IP_M'      => $ip,
                                      'LOGIN_M'   => $nombre,
                                      'FECHA_M2'  => date('Y/m/d'),   //date('d/m/Y')            
                                      'FECHA_M'   => date('Y/m/d')    //date('d/m/Y')                                
                                      ]);
                toastr()->success('Avance del programa anual actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);            
                break;
            case 12:    //Mes diciembre
                $regprogdanual= regProgdAnualModel::where(['FOLIO' => $id, 'PARTIDA' => $id2])        
                                ->update([                
                                      'MES_ID2'   => $request->mes_id2,
                                      'MESC_12'   => $request->avance,
                                      'TRIMC_04'  => $trimc_04,
                                      'TSEMC_02'  => $tsemc_02,
                                      'TOTC_01'   => $totc_01,
                                      'TOTC_02'   => $totc_02,

                                      'MES_P12'   => $mes_p12,
                                      'TRIM_P04'  => $trim_p04,
                                      'SEM_P02'   => $sem_p01,
                                      'TOT_P01'   => $tot_p01,

                                      'SEMAF_12'  => $semaf_12,
                                      'SEMAFT_04' => $semaft_04,
                                      'SEMAFS_02' => $semafs_02,
                                      'SEMAFA_01' => $semafa_01,
                                      'OBS_02'    => substr(trim(strtoupper($request->obs_02)),0,3999),  

                                      'IP_M'      => $ip,
                                      'LOGIN_M'   => $nombre,
                                      'FECHA_M2'  => date('Y/m/d'),   //date('d/m/Y')            
                                      'FECHA_M'   => date('Y/m/d')    //date('d/m/Y')                                
                                      ]);
                toastr()->success('Avance del programa anual actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                break;                                
            }   // fin clase            

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3013;
            $xtrx_id      =        38;    //Actualizar 
            $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID', 'FUNCION_ID', 
                           'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                           ->where(['PERIODO_ID' => $xperiodo_id,  'MES_ID' => $xmes_id, 
                                    'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 
                                    'FOLIO' => $id])
                           ->get();
            if($regbitacora->count() <= 0){              // Alta
                $nuevoregBitacora = new regBitacoraModel();              
                $nuevoregBitacora->PERIODO_ID = $xperiodo_id;    // Año de transaccion 
                $nuevoregBitacora->PROGRAMA_ID= $xprograma_id;   // Proyecto JAPEM 
                $nuevoregBitacora->MES_ID     = $xmes_id;        // Mes de transaccion
                $nuevoregBitacora->PROCESO_ID = $xproceso_id;    // Proceso de apoyo
                $nuevoregBitacora->FUNCION_ID = $xfuncion_id;    // Funcion del modelado de procesos 
                $nuevoregBitacora->TRX_ID     = $xtrx_id;        // Actividad del modelado de procesos
                $nuevoregBitacora->FOLIO      = $id;             // Folio    
                $nuevoregBitacora->NO_VECES   = 1;               // Numero de veces            
                $nuevoregBitacora->IP         = $ip;             // IP
                $nuevoregBitacora->LOGIN      = $nombre;         // Usuario 
                $nuevoregBitacora->save();
                if($nuevoregBitacora->save() == true)
                    toastr()->success('Trx de avance del programa anual registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                else
                    toastr()->error('Error en Trx de avance del programa anual. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
            }else{                   
                //*********** Obtine el no. de veces *****************************
                $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id,'MES_ID'    => $xmes_id,
                                                      'PROCESO_ID' => $xproceso_id,'FUNCION_ID'=> $xfuncion_id, 
                                                      'TRX_ID'     => $xtrx_id    ,'FOLIO'     => $id])
                             ->max('NO_VECES');
                $xno_veces = $xno_veces+1;                        
                //*********** Termina de obtener el no de veces *****************************         
                $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                               ->where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                        'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id, 'FOLIO'      => $id])
                               ->update([
                                         'NO_VECES'=> $regbitacora->NO_VECES = $xno_veces,
                                         'IP_M'    => $regbitacora->IP       = $ip,
                                         'LOGIN_M' => $regbitacora->LOGIN_M  = $nombre,
                                         'FECHA_M' => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                         ]);
                toastr()->success('Trx de avance del programa anual actualizada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/                     
        }       /************ Actualizar *******************************************/
        return redirect()->route('verInformelab',$id);
    }


    public function actionEditarSoportedet1($id,$id2){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $arbol_id     = session()->get('arbol_id');        
        $depen_id     = session()->get('depen_id');                

        $regumedida   = regUmedidaModel::select('UMED_ID','UMED_DESC')
                        ->orderBy('UMED_ID','asc')
                        ->get();      
        $regperiodos  = regPeriodosModel::select('PERIODO_ID', 'PERIODO_DESC')
                        ->get();         
        $reganios     = regAniosModel::select('ANIO_ID','ANIO_DESC')
                        ->get();        
        $regmeses     = regMesesModel::select('MES_ID','MES_DESC')
                        ->get();      
        $regdias      = regDiasModel::select('DIA_ID','DIA_DESC')
                        ->get();                                
        $regepprog    = regEPprogramaModel::select('EPPROG_ID','EPPROG_DESC')
                        ->orderBy('EPPROG_ID','asc')
                        ->get();                         
        $regepproy    = regEPproyectoModel::select('EPPROY_ID','EPPROY_DESC')
                        ->orderBy('EPPROY_ID','asc')
                        ->get();                
        //********* Validar rol de usuario **********************/
        if(session()->get('rango') !== '0'){                          
            $regdepen = regdepenModel::select('DEPEN_ID', 'DEPEN_DESC')
                        ->where('DEPEN_STATUS','1')
                        ->get();                                                         
        }else{
            $regdepen = regdepenModel::select('DEPEN_ID', 'DEPEN_DESC')
                        ->where('DEPEN_STATUS','1')
                        ->where('DEPEN_ID',$depen_id)
                        ->get();               
        }                    
        $regprogeanual= regProgeAnualModel::select('FOLIO','PERIODO_ID','DEPEN_ID1','DEPEN_ID2','DEPEN_ID3',
                        'EPPROG_ID','EPPROY_ID','FECHA_ENTREGA','FECHA_ENTREGA2','FECHA_ENTREGA3',
                        'PERIODO_ID1','MES_ID1','DIA_ID1','MES_ID2','PROGRAMA_ID','PROGRAMA_DESC',
                        'RESPONSABLE','ELABORO','AUTORIZO','OBS_1','OBS_2','STATUS_1','STATUS_2',
                        'FECREG','FECREG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')
                        ->where(  'FOLIO'     ,$id)
                        ->orderBy('PERIODO_ID','ASC')
                        ->orderBy('FOLIO'     ,'ASC')
                        ->get();
        $regprogdanual= regProgdAnualModel::select('FOLIO','PARTIDA','PERIODO_ID','CIPREP_ID','LGOB_COD',
                        'DEPEN_ID1','DEPEN_ID2','DEPEN_ID3','EPPROG_ID','EPPROY_ID',
                        'FECHA_ENTREGA','FECHA_ENTREGA2','FECHA_ENTREGA3','PERIODO_ID1','MES_ID1','DIA_ID1','MES_ID2',
                        'PROGRAMA_ID','PROGRAMA_DESC','ACTIVIDAD_ID','ACTIVIDAD_DESC','OBJETIVO_ID','OBJETIVO_DESC',
                        'OPERACIONAL_DESC','UMED_ID','MESP_01','MESP_02','MESP_03','MESP_04','MESP_05','MESP_06',
                        'MESP_07','MESP_08','MESP_09','MESP_10','MESP_11','MESP_12',
                        'MESC_01','MESC_02','MESC_03','MESC_04','MESC_05','MESC_06',
                        'MESC_07','MESC_08','MESC_09','MESC_10','MESC_11','MESC_12',
                        'TRIMP_01','TRIMP_02','TRIMP_03','TRIMP_04','TOTP_01','TOTP_02',
                        'TRIMC_01','TRIMC_02','TRIMC_03','TRIMC_04','TOTC_01','TOTC_02',
                        'TSEMP_01','TSEMP_02','TSEMC_01','TSEMC_02',   
                        'MES_P01','MES_P02','MES_P03','MES_P04','MES_P05','MES_P06',
                        'MES_P07','MES_P08','MES_P09','MES_P10','MES_P11','MES_P12',
                        'TRIM_P01','TRIM_P02','TRIM_P03','TRIM_P04','SEM_P01','SEM_P02','TOT_P01',
                        'SEMAF_01','SEMAF_02','SEMAF_03','SEMAF_04','SEMAF_05','SEMAF_06',
                        'SEMAF_07','SEMAF_08','SEMAF_09','SEMAF_10','SEMAF_11','SEMAF_12',
                        'SEMAFT_01','SEMAFT_02','SEMAFT_03','SEMAFT_04','SEMAFS_01','SEMAFS_02','SEMAFA_01',
                        'SOPORTE_ID','SOPORTE_01','SOPORTE_02','SOPORTE_03','SOPORTE_04','SOPORTE_05','SOPORTE_06',
                        'SOPORTE_07','SOPORTE_08','SOPORTE_09','SOPORTE_10','SOPORTE_11','SOPORTE_12',
                        'OBS_01','OBS_02',
                        'STATUS_1','STATUS_2','FECREG','FECREG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')               
                        ->where(['FOLIO' => $id, 'PARTIDA' => $id2])
                        //->where('FOLIO',$id)
                        //->where('PARTIDA',$id2)
                        ->first();  
        if($regprogdanual->count() <= 0){
            toastr()->error('No existen registros de acciones o metas del Programa anual.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }
        return view('sicinar.informelabores.editarSoportedet1',compact('nombre','usuario','regdepen','reganios','regperiodos','regmeses','regdias','regprogeanual','regprogdanual','regumedida','regepproy','regepprog'));
    }

    public function actionActualizarSoportedet1(soportedet1Request $request, $id, $id2){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango');
        $ip            = session()->get('ip');
        $arbol_id      = session()->get('arbol_id');    
        $depen_id      = session()->get('depen_id');                    

        // **************** actualizar ******************************
        $regprogdanual= regProgdAnualModel::where(['FOLIO' => $id, 'PARTIDA' => $id2]);
        if($regprogdanual->count() <= 0)
            toastr()->error('No existe acción o meta del programa anual.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            //********************** Actualizar ********************************/
            $name01 =null;
            if($request->hasFile('soporte_01')){
                $name01 = $request->periodo_id.'_'.$id.'_'.$id2.'_'.$request->file('soporte_01')->getClientOriginalName(); 
                $request->file('soporte_01')->move(public_path().'/storage/', $name01);

                $regprogdanual = regProgdAnualModel::where(['FOLIO' => $id, 'PARTIDA' => $id2])        
                                 ->update([                
                                           'SOPORTE_01' => $name01,

                                           'IP_M'       => $ip,
                                           'LOGIN_M'    => $nombre,
                                           'FECHA_M2'   => date('Y/m/d'),    //date('d/m/Y')
                                           'FECHA_M'    => date('Y/m/d')    //date('d/m/Y')                                
                                           ]);
                toastr()->success('Archivo digital justificación actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);

                /************ Bitacora inicia *************************************/ 
                setlocale(LC_TIME, "spanish");        
                $xip          = session()->get('ip');
                $xperiodo_id  = (int)date('Y');
                $xprograma_id = 1;
                $xmes_id      = (int)date('m');
                $xproceso_id  =         3;
                $xfuncion_id  =      3013;
                $xtrx_id      =        38;    //Actualizar 
                $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID', 'FUNCION_ID', 
                               'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                               ->where(['PERIODO_ID' => $xperiodo_id,  'MES_ID' => $xmes_id, 
                                        'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 
                                        'FOLIO' => $id])
                               ->get();
                if($regbitacora->count() <= 0){              // Alta
                    $nuevoregBitacora = new regBitacoraModel();              
                    $nuevoregBitacora->PERIODO_ID = $xperiodo_id;    // Año de transaccion 
                    $nuevoregBitacora->PROGRAMA_ID= $xprograma_id;   // Proyecto JAPEM 
                    $nuevoregBitacora->MES_ID     = $xmes_id;        // Mes de transaccion
                    $nuevoregBitacora->PROCESO_ID = $xproceso_id;    // Proceso de apoyo
                    $nuevoregBitacora->FUNCION_ID = $xfuncion_id;    // Funcion del modelado de procesos 
                    $nuevoregBitacora->TRX_ID     = $xtrx_id;        // Actividad del modelado de procesos
                    $nuevoregBitacora->FOLIO      = $id;             // Folio    
                    $nuevoregBitacora->NO_VECES   = 1;               // Numero de veces            
                    $nuevoregBitacora->IP         = $ip;             // IP
                    $nuevoregBitacora->LOGIN      = $nombre;         // Usuario 
                    $nuevoregBitacora->save();
                    if($nuevoregBitacora->save() == true)
                        toastr()->success('Trx de Archivo digital de justificación registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                    else
                        toastr()->error('Error en Trx de Archivo digital de justificación. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
                }else{                   
                    //*********** Obtine el no. de veces *****************************
                    $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id,'MES_ID'    => $xmes_id,
                                                          'PROCESO_ID' => $xproceso_id,'FUNCION_ID'=> $xfuncion_id, 
                                                          'TRX_ID'     => $xtrx_id    ,'FOLIO'     => $id])
                                 ->max('NO_VECES');
                    $xno_veces = $xno_veces+1;                        
                    //*********** Termina de obtener el no de veces *****************************         
                    $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                                   ->where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                            'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id, 'FOLIO'      => $id])
                                   ->update([
                                             'NO_VECES'=> $regbitacora->NO_VECES = $xno_veces,
                                             'IP_M'    => $regbitacora->IP       = $ip,
                                             'LOGIN_M' => $regbitacora->LOGIN_M  = $nombre,
                                             'FECHA_M' => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                             ]);
                    toastr()->success('Trx de Archivo digital de justificación actualizado en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                }   /************ Bitacora termina *************************************/       
            }       /************ Valida si viene vacio el arc. digital ****************/                              
        }           /************ Actualizar *******************************************/
        return redirect()->route('versoportesdet',$id);
    }

   public function actionEditarSoportedet2($id,$id2){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $arbol_id     = session()->get('arbol_id');        
        $depen_id     = session()->get('depen_id');                

        $regumedida   = regUmedidaModel::select('UMED_ID','UMED_DESC')
                        ->orderBy('UMED_ID','asc')
                        ->get();      
        $regperiodos  = regPeriodosModel::select('PERIODO_ID', 'PERIODO_DESC')
                        ->get();         
        $reganios     = regAniosModel::select('ANIO_ID','ANIO_DESC')
                        ->get();        
        $regmeses     = regMesesModel::select('MES_ID','MES_DESC')
                        ->get();      
        $regdias      = regDiasModel::select('DIA_ID','DIA_DESC')
                        ->get();                                
        $regepprog    = regEPprogramaModel::select('EPPROG_ID','EPPROG_DESC')
                        ->orderBy('EPPROG_ID','asc')
                        ->get();                         
        $regepproy    = regEPproyectoModel::select('EPPROY_ID','EPPROY_DESC')
                        ->orderBy('EPPROY_ID','asc')
                        ->get();                
        //********* Validar rol de usuario **********************/
        if(session()->get('rango') !== '0'){                          
            $regdepen = regdepenModel::select('DEPEN_ID', 'DEPEN_DESC')
                        ->where('DEPEN_STATUS','1')
                        ->get();                                                         
        }else{
            $regdepen = regdepenModel::select('DEPEN_ID', 'DEPEN_DESC')
                        ->where('DEPEN_STATUS','1')
                        ->where('DEPEN_ID',$depen_id)
                        ->get();               
        }                    
        $regprogeanual= regProgeAnualModel::select('FOLIO','PERIODO_ID','DEPEN_ID1','DEPEN_ID2','DEPEN_ID3',
                        'EPPROG_ID','EPPROY_ID','FECHA_ENTREGA','FECHA_ENTREGA2','FECHA_ENTREGA3',
                        'PERIODO_ID1','MES_ID1','DIA_ID1','MES_ID2','PROGRAMA_ID','PROGRAMA_DESC',
                        'RESPONSABLE','ELABORO','AUTORIZO','OBS_1','OBS_2','STATUS_1','STATUS_2',
                        'FECREG','FECREG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')
                        ->where(  'FOLIO'     ,$id)
                        ->orderBy('PERIODO_ID','ASC')
                        ->orderBy('FOLIO'     ,'ASC')
                        ->get();
        $regprogdanual= regProgdAnualModel::select('FOLIO','PARTIDA','PERIODO_ID','CIPREP_ID','LGOB_COD',
                        'DEPEN_ID1','DEPEN_ID2','DEPEN_ID3','EPPROG_ID','EPPROY_ID',
                        'FECHA_ENTREGA','FECHA_ENTREGA2','FECHA_ENTREGA3','PERIODO_ID1','MES_ID1','DIA_ID1','MES_ID2',
                        'PROGRAMA_ID','PROGRAMA_DESC','ACTIVIDAD_ID','ACTIVIDAD_DESC','OBJETIVO_ID','OBJETIVO_DESC',
                        'OPERACIONAL_DESC','UMED_ID','MESP_01','MESP_02','MESP_03','MESP_04','MESP_05','MESP_06',
                        'MESP_07','MESP_08','MESP_09','MESP_10','MESP_11','MESP_12',
                        'MESC_01','MESC_02','MESC_03','MESC_04','MESC_05','MESC_06',
                        'MESC_07','MESC_08','MESC_09','MESC_10','MESC_11','MESC_12',
                        'TRIMP_01','TRIMP_02','TRIMP_03','TRIMP_04','TOTP_01','TOTP_02',
                        'TRIMC_01','TRIMC_02','TRIMC_03','TRIMC_04','TOTC_01','TOTC_02',
                        'TSEMP_01','TSEMP_02','TSEMC_01','TSEMC_02',   
                        'MES_P01','MES_P02','MES_P03','MES_P04','MES_P05','MES_P06',
                        'MES_P07','MES_P08','MES_P09','MES_P10','MES_P11','MES_P12',
                        'TRIM_P01','TRIM_P02','TRIM_P03','TRIM_P04','SEM_P01','SEM_P02','TOT_P01',
                        'SEMAF_01','SEMAF_02','SEMAF_03','SEMAF_04','SEMAF_05','SEMAF_06',
                        'SEMAF_07','SEMAF_08','SEMAF_09','SEMAF_10','SEMAF_11','SEMAF_12',
                        'SEMAFT_01','SEMAFT_02','SEMAFT_03','SEMAFT_04','SEMAFS_01','SEMAFS_02','SEMAFA_01',
                        'SOPORTE_ID','SOPORTE_01','SOPORTE_02','SOPORTE_03','SOPORTE_04','SOPORTE_05','SOPORTE_06',
                        'SOPORTE_07','SOPORTE_08','SOPORTE_09','SOPORTE_10','SOPORTE_11','SOPORTE_12',
                        'OBS_01','OBS_02',
                        'STATUS_1','STATUS_2','FECREG','FECREG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')               
                        ->where(['FOLIO' => $id, 'PARTIDA' => $id2])
                        //->where('FOLIO',$id)
                        //->where('PARTIDA',$id2)
                        ->first();  
        if($regprogdanual->count() <= 0){
            toastr()->error('No existen registros de acciones o metas del Programa anual.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }
        return view('sicinar.informelabores.editarSoportedet2',compact('nombre','usuario','regdepen','reganios','regperiodos','regmeses','regdias','regprogeanual','regprogdanual','regumedida','regepproy','regepprog'));
    }

    public function actionActualizarSoportedet2(soportedet2Request $request, $id, $id2){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango');
        $ip            = session()->get('ip');
        $arbol_id      = session()->get('arbol_id');    
        $depen_id      = session()->get('depen_id');                    

        // **************** actualizar ******************************
        $regprogdanual= regProgdAnualModel::where(['FOLIO' => $id, 'PARTIDA' => $id2]);
        if($regprogdanual->count() <= 0)
            toastr()->error('No existe acción o meta del programa anual.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            //********************** Actualizar ********************************/
            $name02 =null;
            if($request->hasFile('soporte_02')){
                $name02 = $request->periodo_id.'_'.$id.'_'.$id2.'_'.$request->file('soporte_02')->getClientOriginalName(); 
                $request->file('soporte_02')->move(public_path().'/storage/', $name02);

                $regprogdanual = regProgdAnualModel::where(['FOLIO' => $id, 'PARTIDA' => $id2])        
                                 ->update([                
                                           'SOPORTE_02' => $name02,

                                           'IP_M'       => $ip,
                                           'LOGIN_M'    => $nombre,
                                           'FECHA_M2'   => date('Y/m/d'),    //date('d/m/Y')
                                           'FECHA_M'    => date('Y/m/d')    //date('d/m/Y')                                
                                           ]);
                toastr()->success('Archivo digital justificación actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);

                /************ Bitacora inicia *************************************/ 
                setlocale(LC_TIME, "spanish");        
                $xip          = session()->get('ip');
                $xperiodo_id  = (int)date('Y');
                $xprograma_id = 1;
                $xmes_id      = (int)date('m');
                $xproceso_id  =         3;
                $xfuncion_id  =      3013;
                $xtrx_id      =        38;    //Actualizar 
                $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID', 'FUNCION_ID', 
                               'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                               ->where(['PERIODO_ID' => $xperiodo_id,  'MES_ID' => $xmes_id, 
                                        'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 
                                        'FOLIO' => $id])
                               ->get();
                if($regbitacora->count() <= 0){              // Alta
                    $nuevoregBitacora = new regBitacoraModel();              
                    $nuevoregBitacora->PERIODO_ID = $xperiodo_id;    // Año de transaccion 
                    $nuevoregBitacora->PROGRAMA_ID= $xprograma_id;   // Proyecto JAPEM 
                    $nuevoregBitacora->MES_ID     = $xmes_id;        // Mes de transaccion
                    $nuevoregBitacora->PROCESO_ID = $xproceso_id;    // Proceso de apoyo
                    $nuevoregBitacora->FUNCION_ID = $xfuncion_id;    // Funcion del modelado de procesos 
                    $nuevoregBitacora->TRX_ID     = $xtrx_id;        // Actividad del modelado de procesos
                    $nuevoregBitacora->FOLIO      = $id;             // Folio    
                    $nuevoregBitacora->NO_VECES   = 1;               // Numero de veces            
                    $nuevoregBitacora->IP         = $ip;             // IP
                    $nuevoregBitacora->LOGIN      = $nombre;         // Usuario 
                    $nuevoregBitacora->save();
                    if($nuevoregBitacora->save() == true)
                        toastr()->success('Trx de Archivo digital de justificación registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                    else
                        toastr()->error('Error en Trx de Archivo digital de justificación. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
                }else{                   
                    //*********** Obtine el no. de veces *****************************
                    $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id,'MES_ID'    => $xmes_id,
                                                          'PROCESO_ID' => $xproceso_id,'FUNCION_ID'=> $xfuncion_id, 
                                                          'TRX_ID'     => $xtrx_id    ,'FOLIO'     => $id])
                                 ->max('NO_VECES');
                    $xno_veces = $xno_veces+1;                        
                    //*********** Termina de obtener el no de veces *****************************         
                    $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                                   ->where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                            'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id, 'FOLIO'      => $id])
                                   ->update([
                                             'NO_VECES'=> $regbitacora->NO_VECES = $xno_veces,
                                             'IP_M'    => $regbitacora->IP       = $ip,
                                             'LOGIN_M' => $regbitacora->LOGIN_M  = $nombre,
                                             'FECHA_M' => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                             ]);
                    toastr()->success('Trx de Archivo digital de justificación actualizado en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                }   /************ Bitacora termina *************************************/       
            }       /************ Valida si viene vacio el arc. digital ****************/                              
        }           /************ Actualizar *******************************************/
        return redirect()->route('versoportesdet',$id);
    }

   public function actionEditarSoportedet3($id,$id2){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $arbol_id     = session()->get('arbol_id');        
        $depen_id     = session()->get('depen_id');                

        $regumedida   = regUmedidaModel::select('UMED_ID','UMED_DESC')
                        ->orderBy('UMED_ID','asc')
                        ->get();      
        $regperiodos  = regPeriodosModel::select('PERIODO_ID', 'PERIODO_DESC')
                        ->get();         
        $reganios     = regAniosModel::select('ANIO_ID','ANIO_DESC')
                        ->get();        
        $regmeses     = regMesesModel::select('MES_ID','MES_DESC')
                        ->get();      
        $regdias      = regDiasModel::select('DIA_ID','DIA_DESC')
                        ->get();                                
        $regepprog    = regEPprogramaModel::select('EPPROG_ID','EPPROG_DESC')
                        ->orderBy('EPPROG_ID','asc')
                        ->get();                         
        $regepproy    = regEPproyectoModel::select('EPPROY_ID','EPPROY_DESC')
                        ->orderBy('EPPROY_ID','asc')
                        ->get();                
        //********* Validar rol de usuario **********************/
        if(session()->get('rango') !== '0'){                          
            $regdepen = regdepenModel::select('DEPEN_ID', 'DEPEN_DESC')
                        ->where('DEPEN_STATUS','1')
                        ->get();                                                         
        }else{
            $regdepen = regdepenModel::select('DEPEN_ID', 'DEPEN_DESC')
                        ->where('DEPEN_STATUS','1')
                        ->where('DEPEN_ID',$depen_id)
                        ->get();               
        }                    
        $regprogeanual= regProgeAnualModel::select('FOLIO','PERIODO_ID','DEPEN_ID1','DEPEN_ID2','DEPEN_ID3',
                        'EPPROG_ID','EPPROY_ID','FECHA_ENTREGA','FECHA_ENTREGA2','FECHA_ENTREGA3',
                        'PERIODO_ID1','MES_ID1','DIA_ID1','MES_ID2','PROGRAMA_ID','PROGRAMA_DESC',
                        'RESPONSABLE','ELABORO','AUTORIZO','OBS_1','OBS_2','STATUS_1','STATUS_2',
                        'FECREG','FECREG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')
                        ->where(  'FOLIO'     ,$id)
                        ->orderBy('PERIODO_ID','ASC')
                        ->orderBy('FOLIO'     ,'ASC')
                        ->get();
        $regprogdanual= regProgdAnualModel::select('FOLIO','PARTIDA','PERIODO_ID','CIPREP_ID','LGOB_COD',
                        'DEPEN_ID1','DEPEN_ID2','DEPEN_ID3','EPPROG_ID','EPPROY_ID',
                        'FECHA_ENTREGA','FECHA_ENTREGA2','FECHA_ENTREGA3','PERIODO_ID1','MES_ID1','DIA_ID1','MES_ID2',
                        'PROGRAMA_ID','PROGRAMA_DESC','ACTIVIDAD_ID','ACTIVIDAD_DESC','OBJETIVO_ID','OBJETIVO_DESC',
                        'OPERACIONAL_DESC','UMED_ID','MESP_01','MESP_02','MESP_03','MESP_04','MESP_05','MESP_06',
                        'MESP_07','MESP_08','MESP_09','MESP_10','MESP_11','MESP_12',
                        'MESC_01','MESC_02','MESC_03','MESC_04','MESC_05','MESC_06',
                        'MESC_07','MESC_08','MESC_09','MESC_10','MESC_11','MESC_12',
                        'TRIMP_01','TRIMP_02','TRIMP_03','TRIMP_04','TOTP_01','TOTP_02',
                        'TRIMC_01','TRIMC_02','TRIMC_03','TRIMC_04','TOTC_01','TOTC_02',
                        'TSEMP_01','TSEMP_02','TSEMC_01','TSEMC_02',   
                        'MES_P01','MES_P02','MES_P03','MES_P04','MES_P05','MES_P06',
                        'MES_P07','MES_P08','MES_P09','MES_P10','MES_P11','MES_P12',
                        'TRIM_P01','TRIM_P02','TRIM_P03','TRIM_P04','SEM_P01','SEM_P02','TOT_P01',
                        'SEMAF_01','SEMAF_02','SEMAF_03','SEMAF_04','SEMAF_05','SEMAF_06',
                        'SEMAF_07','SEMAF_08','SEMAF_09','SEMAF_10','SEMAF_11','SEMAF_12',
                        'SEMAFT_01','SEMAFT_02','SEMAFT_03','SEMAFT_04','SEMAFS_01','SEMAFS_02','SEMAFA_01',
                        'SOPORTE_ID','SOPORTE_01','SOPORTE_02','SOPORTE_03','SOPORTE_04','SOPORTE_05','SOPORTE_06',
                        'SOPORTE_07','SOPORTE_08','SOPORTE_09','SOPORTE_10','SOPORTE_11','SOPORTE_12',
                        'OBS_01','OBS_02',
                        'STATUS_1','STATUS_2','FECREG','FECREG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')               
                        ->where(['FOLIO' => $id, 'PARTIDA' => $id2])
                        //->where('FOLIO',$id)
                        //->where('PARTIDA',$id2)
                        ->first();  
        if($regprogdanual->count() <= 0){
            toastr()->error('No existen registros de acciones o metas del Programa anual.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }
        return view('sicinar.informelabores.editarSoportedet3',compact('nombre','usuario','regdepen','reganios','regperiodos','regmeses','regdias','regprogeanual','regprogdanual','regumedida','regepproy','regepprog'));
    }

    public function actionActualizarSoportedet3(soportedet3Request $request, $id, $id2){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango');
        $ip            = session()->get('ip');
        $arbol_id      = session()->get('arbol_id');    
        $depen_id      = session()->get('depen_id');                    

        // **************** actualizar ******************************
        $regprogdanual= regProgdAnualModel::where(['FOLIO' => $id, 'PARTIDA' => $id2]);
        if($regprogdanual->count() <= 0)
            toastr()->error('No existe acción o meta del programa anual.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            //********************** Actualizar ********************************/
            $name03 =null;
            if($request->hasFile('soporte_03')){
                $name03 = $request->periodo_id.'_'.$id.'_'.$id2.'_'.$request->file('soporte_03')->getClientOriginalName(); 
                $request->file('soporte_03')->move(public_path().'/storage/', $name03);

                $regprogdanual = regProgdAnualModel::where(['FOLIO' => $id, 'PARTIDA' => $id2])        
                                 ->update([                
                                           'SOPORTE_03' => $name03,

                                           'IP_M'       => $ip,
                                           'LOGIN_M'    => $nombre,
                                           'FECHA_M2'   => date('Y/m/d'),    //date('d/m/Y')
                                           'FECHA_M'    => date('Y/m/d')    //date('d/m/Y')                                
                                           ]);
                toastr()->success('Archivo digital justificación actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);

                /************ Bitacora inicia *************************************/ 
                setlocale(LC_TIME, "spanish");        
                $xip          = session()->get('ip');
                $xperiodo_id  = (int)date('Y');
                $xprograma_id = 1;
                $xmes_id      = (int)date('m');
                $xproceso_id  =         3;
                $xfuncion_id  =      3013;
                $xtrx_id      =        38;    //Actualizar 
                $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID', 'FUNCION_ID', 
                               'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                               ->where(['PERIODO_ID' => $xperiodo_id,  'MES_ID' => $xmes_id, 
                                        'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 
                                        'FOLIO' => $id])
                               ->get();
                if($regbitacora->count() <= 0){              // Alta
                    $nuevoregBitacora = new regBitacoraModel();              
                    $nuevoregBitacora->PERIODO_ID = $xperiodo_id;    // Año de transaccion 
                    $nuevoregBitacora->PROGRAMA_ID= $xprograma_id;   // Proyecto JAPEM 
                    $nuevoregBitacora->MES_ID     = $xmes_id;        // Mes de transaccion
                    $nuevoregBitacora->PROCESO_ID = $xproceso_id;    // Proceso de apoyo
                    $nuevoregBitacora->FUNCION_ID = $xfuncion_id;    // Funcion del modelado de procesos 
                    $nuevoregBitacora->TRX_ID     = $xtrx_id;        // Actividad del modelado de procesos
                    $nuevoregBitacora->FOLIO      = $id;             // Folio    
                    $nuevoregBitacora->NO_VECES   = 1;               // Numero de veces            
                    $nuevoregBitacora->IP         = $ip;             // IP
                    $nuevoregBitacora->LOGIN      = $nombre;         // Usuario 
                    $nuevoregBitacora->save();
                    if($nuevoregBitacora->save() == true)
                        toastr()->success('Trx de Archivo digital de justificación registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                    else
                        toastr()->error('Error en Trx de Archivo digital de justificación. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
                }else{                   
                    //*********** Obtine el no. de veces *****************************
                    $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id,'MES_ID'    => $xmes_id,
                                                          'PROCESO_ID' => $xproceso_id,'FUNCION_ID'=> $xfuncion_id, 
                                                          'TRX_ID'     => $xtrx_id    ,'FOLIO'     => $id])
                                 ->max('NO_VECES');
                    $xno_veces = $xno_veces+1;                        
                    //*********** Termina de obtener el no de veces *****************************         
                    $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                                   ->where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                            'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id, 'FOLIO'      => $id])
                                   ->update([
                                             'NO_VECES'=> $regbitacora->NO_VECES = $xno_veces,
                                             'IP_M'    => $regbitacora->IP       = $ip,
                                             'LOGIN_M' => $regbitacora->LOGIN_M  = $nombre,
                                             'FECHA_M' => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                             ]);
                    toastr()->success('Trx de Archivo digital de justificación actualizado en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                }   /************ Bitacora termina *************************************/       
            }       /************ Valida si viene vacio el arc. digital ****************/                              
        }           /************ Actualizar *******************************************/
        return redirect()->route('versoportesdet',$id);
    }

   public function actionEditarSoportedet4($id,$id2){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $arbol_id     = session()->get('arbol_id');        
        $depen_id     = session()->get('depen_id');                

        $regumedida   = regUmedidaModel::select('UMED_ID','UMED_DESC')
                        ->orderBy('UMED_ID','asc')
                        ->get();      
        $regperiodos  = regPeriodosModel::select('PERIODO_ID', 'PERIODO_DESC')
                        ->get();         
        $reganios     = regAniosModel::select('ANIO_ID','ANIO_DESC')
                        ->get();        
        $regmeses     = regMesesModel::select('MES_ID','MES_DESC')
                        ->get();      
        $regdias      = regDiasModel::select('DIA_ID','DIA_DESC')
                        ->get();                                
        $regepprog    = regEPprogramaModel::select('EPPROG_ID','EPPROG_DESC')
                        ->orderBy('EPPROG_ID','asc')
                        ->get();                         
        $regepproy    = regEPproyectoModel::select('EPPROY_ID','EPPROY_DESC')
                        ->orderBy('EPPROY_ID','asc')
                        ->get();                
        //********* Validar rol de usuario **********************/
        if(session()->get('rango') !== '0'){                          
            $regdepen = regdepenModel::select('DEPEN_ID', 'DEPEN_DESC')
                        ->where('DEPEN_STATUS','1')
                        ->get();                                                         
        }else{
            $regdepen = regdepenModel::select('DEPEN_ID', 'DEPEN_DESC')
                        ->where('DEPEN_STATUS','1')
                        ->where('DEPEN_ID',$depen_id)
                        ->get();               
        }                    
        $regprogeanual= regProgeAnualModel::select('FOLIO','PERIODO_ID','DEPEN_ID1','DEPEN_ID2','DEPEN_ID3',
                        'EPPROG_ID','EPPROY_ID','FECHA_ENTREGA','FECHA_ENTREGA2','FECHA_ENTREGA3',
                        'PERIODO_ID1','MES_ID1','DIA_ID1','MES_ID2','PROGRAMA_ID','PROGRAMA_DESC',
                        'RESPONSABLE','ELABORO','AUTORIZO','OBS_1','OBS_2','STATUS_1','STATUS_2',
                        'FECREG','FECREG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')
                        ->where(  'FOLIO'     ,$id)
                        ->orderBy('PERIODO_ID','ASC')
                        ->orderBy('FOLIO'     ,'ASC')
                        ->get();
        $regprogdanual= regProgdAnualModel::select('FOLIO','PARTIDA','PERIODO_ID','CIPREP_ID','LGOB_COD',
                        'DEPEN_ID1','DEPEN_ID2','DEPEN_ID3','EPPROG_ID','EPPROY_ID',
                        'FECHA_ENTREGA','FECHA_ENTREGA2','FECHA_ENTREGA3','PERIODO_ID1','MES_ID1','DIA_ID1','MES_ID2',
                        'PROGRAMA_ID','PROGRAMA_DESC','ACTIVIDAD_ID','ACTIVIDAD_DESC','OBJETIVO_ID','OBJETIVO_DESC',
                        'OPERACIONAL_DESC','UMED_ID','MESP_01','MESP_02','MESP_03','MESP_04','MESP_05','MESP_06',
                        'MESP_07','MESP_08','MESP_09','MESP_10','MESP_11','MESP_12',
                        'MESC_01','MESC_02','MESC_03','MESC_04','MESC_05','MESC_06',
                        'MESC_07','MESC_08','MESC_09','MESC_10','MESC_11','MESC_12',
                        'TRIMP_01','TRIMP_02','TRIMP_03','TRIMP_04','TOTP_01','TOTP_02',
                        'TRIMC_01','TRIMC_02','TRIMC_03','TRIMC_04','TOTC_01','TOTC_02',
                        'TSEMP_01','TSEMP_02','TSEMC_01','TSEMC_02',   
                        'MES_P01','MES_P02','MES_P03','MES_P04','MES_P05','MES_P06',
                        'MES_P07','MES_P08','MES_P09','MES_P10','MES_P11','MES_P12',
                        'TRIM_P01','TRIM_P02','TRIM_P03','TRIM_P04','SEM_P01','SEM_P02','TOT_P01',
                        'SEMAF_01','SEMAF_02','SEMAF_03','SEMAF_04','SEMAF_05','SEMAF_06',
                        'SEMAF_07','SEMAF_08','SEMAF_09','SEMAF_10','SEMAF_11','SEMAF_12',
                        'SEMAFT_01','SEMAFT_02','SEMAFT_03','SEMAFT_04','SEMAFS_01','SEMAFS_02','SEMAFA_01',
                        'SOPORTE_ID','SOPORTE_01','SOPORTE_02','SOPORTE_03','SOPORTE_04','SOPORTE_05','SOPORTE_06',
                        'SOPORTE_07','SOPORTE_08','SOPORTE_09','SOPORTE_10','SOPORTE_11','SOPORTE_12',
                        'OBS_01','OBS_02',
                        'STATUS_1','STATUS_2','FECREG','FECREG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')               
                        ->where(['FOLIO' => $id, 'PARTIDA' => $id2])
                        //->where('FOLIO',$id)
                        //->where('PARTIDA',$id2)
                        ->first();  
        if($regprogdanual->count() <= 0){
            toastr()->error('No existen registros de acciones o metas del Programa anual.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }
        return view('sicinar.informelabores.editarSoportedet4',compact('nombre','usuario','regdepen','reganios','regperiodos','regmeses','regdias','regprogeanual','regprogdanual','regumedida','regepproy','regepprog'));
    }

    public function actionActualizarSoportedet4(soportedet4Request $request, $id, $id2){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango');
        $ip            = session()->get('ip');
        $arbol_id      = session()->get('arbol_id');    
        $depen_id      = session()->get('depen_id');                    

        // **************** actualizar ******************************
        $regprogdanual= regProgdAnualModel::where(['FOLIO' => $id, 'PARTIDA' => $id2]);
        if($regprogdanual->count() <= 0)
            toastr()->error('No existe acción o meta del programa anual.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            //********************** Actualizar ********************************/
            $name04 =null;
            if($request->hasFile('soporte_04')){
                $name04 = $request->periodo_id.'_'.$id.'_'.$id2.'_'.$request->file('soporte_04')->getClientOriginalName(); 
                $request->file('soporte_04')->move(public_path().'/storage/', $name04);

                $regprogdanual = regProgdAnualModel::where(['FOLIO' => $id, 'PARTIDA' => $id2])        
                                 ->update([                
                                           'SOPORTE_04' => $name04,

                                           'IP_M'       => $ip,
                                           'LOGIN_M'    => $nombre,
                                           'FECHA_M2'   => date('Y/m/d'),    //date('d/m/Y')
                                           'FECHA_M'    => date('Y/m/d')    //date('d/m/Y')                                
                                           ]);
                toastr()->success('Archivo digital justificación actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);

                /************ Bitacora inicia *************************************/ 
                setlocale(LC_TIME, "spanish");        
                $xip          = session()->get('ip');
                $xperiodo_id  = (int)date('Y');
                $xprograma_id = 1;
                $xmes_id      = (int)date('m');
                $xproceso_id  =         3;
                $xfuncion_id  =      3013;
                $xtrx_id      =        38;    //Actualizar 
                $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID', 'FUNCION_ID', 
                               'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                               ->where(['PERIODO_ID' => $xperiodo_id,  'MES_ID' => $xmes_id, 
                                        'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 
                                        'FOLIO' => $id])
                               ->get();
                if($regbitacora->count() <= 0){              // Alta
                    $nuevoregBitacora = new regBitacoraModel();              
                    $nuevoregBitacora->PERIODO_ID = $xperiodo_id;    // Año de transaccion 
                    $nuevoregBitacora->PROGRAMA_ID= $xprograma_id;   // Proyecto JAPEM 
                    $nuevoregBitacora->MES_ID     = $xmes_id;        // Mes de transaccion
                    $nuevoregBitacora->PROCESO_ID = $xproceso_id;    // Proceso de apoyo
                    $nuevoregBitacora->FUNCION_ID = $xfuncion_id;    // Funcion del modelado de procesos 
                    $nuevoregBitacora->TRX_ID     = $xtrx_id;        // Actividad del modelado de procesos
                    $nuevoregBitacora->FOLIO      = $id;             // Folio    
                    $nuevoregBitacora->NO_VECES   = 1;               // Numero de veces            
                    $nuevoregBitacora->IP         = $ip;             // IP
                    $nuevoregBitacora->LOGIN      = $nombre;         // Usuario 
                    $nuevoregBitacora->save();
                    if($nuevoregBitacora->save() == true)
                        toastr()->success('Trx de Archivo digital de justificación registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                    else
                        toastr()->error('Error en Trx de Archivo digital de justificación. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
                }else{                   
                    //*********** Obtine el no. de veces *****************************
                    $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id,'MES_ID'    => $xmes_id,
                                                          'PROCESO_ID' => $xproceso_id,'FUNCION_ID'=> $xfuncion_id, 
                                                          'TRX_ID'     => $xtrx_id    ,'FOLIO'     => $id])
                                 ->max('NO_VECES');
                    $xno_veces = $xno_veces+1;                        
                    //*********** Termina de obtener el no de veces *****************************         
                    $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                                   ->where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                            'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id, 'FOLIO'      => $id])
                                   ->update([
                                             'NO_VECES'=> $regbitacora->NO_VECES = $xno_veces,
                                             'IP_M'    => $regbitacora->IP       = $ip,
                                             'LOGIN_M' => $regbitacora->LOGIN_M  = $nombre,
                                             'FECHA_M' => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                             ]);
                    toastr()->success('Trx de Archivo digital de justificación actualizado en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                }   /************ Bitacora termina *************************************/       
            }       /************ Valida si viene vacio el arc. digital ****************/                              
        }           /************ Actualizar *******************************************/
        return redirect()->route('versoportesdet',$id);
    }    

   public function actionEditarSoportedet5($id,$id2){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $arbol_id     = session()->get('arbol_id');        
        $depen_id     = session()->get('depen_id');                

        $regumedida   = regUmedidaModel::select('UMED_ID','UMED_DESC')
                        ->orderBy('UMED_ID','asc')
                        ->get();      
        $regperiodos  = regPeriodosModel::select('PERIODO_ID', 'PERIODO_DESC')
                        ->get();         
        $reganios     = regAniosModel::select('ANIO_ID','ANIO_DESC')
                        ->get();        
        $regmeses     = regMesesModel::select('MES_ID','MES_DESC')
                        ->get();      
        $regdias      = regDiasModel::select('DIA_ID','DIA_DESC')
                        ->get();                                
        $regepprog    = regEPprogramaModel::select('EPPROG_ID','EPPROG_DESC')
                        ->orderBy('EPPROG_ID','asc')
                        ->get();                         
        $regepproy    = regEPproyectoModel::select('EPPROY_ID','EPPROY_DESC')
                        ->orderBy('EPPROY_ID','asc')
                        ->get();                
        //********* Validar rol de usuario **********************/
        if(session()->get('rango') !== '0'){                          
            $regdepen = regdepenModel::select('DEPEN_ID', 'DEPEN_DESC')
                        ->where('DEPEN_STATUS','1')
                        ->get();                                                         
        }else{
            $regdepen = regdepenModel::select('DEPEN_ID', 'DEPEN_DESC')
                        ->where('DEPEN_STATUS','1')
                        ->where('DEPEN_ID',$depen_id)
                        ->get();               
        }                    
        $regprogeanual= regProgeAnualModel::select('FOLIO','PERIODO_ID','DEPEN_ID1','DEPEN_ID2','DEPEN_ID3',
                        'EPPROG_ID','EPPROY_ID','FECHA_ENTREGA','FECHA_ENTREGA2','FECHA_ENTREGA3',
                        'PERIODO_ID1','MES_ID1','DIA_ID1','MES_ID2','PROGRAMA_ID','PROGRAMA_DESC',
                        'RESPONSABLE','ELABORO','AUTORIZO','OBS_1','OBS_2','STATUS_1','STATUS_2',
                        'FECREG','FECREG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')
                        ->where(  'FOLIO'     ,$id)
                        ->orderBy('PERIODO_ID','ASC')
                        ->orderBy('FOLIO'     ,'ASC')
                        ->get();
        $regprogdanual= regProgdAnualModel::select('FOLIO','PARTIDA','PERIODO_ID','CIPREP_ID','LGOB_COD',
                        'DEPEN_ID1','DEPEN_ID2','DEPEN_ID3','EPPROG_ID','EPPROY_ID',
                        'FECHA_ENTREGA','FECHA_ENTREGA2','FECHA_ENTREGA3','PERIODO_ID1','MES_ID1','DIA_ID1','MES_ID2',
                        'PROGRAMA_ID','PROGRAMA_DESC','ACTIVIDAD_ID','ACTIVIDAD_DESC','OBJETIVO_ID','OBJETIVO_DESC',
                        'OPERACIONAL_DESC','UMED_ID','MESP_01','MESP_02','MESP_03','MESP_04','MESP_05','MESP_06',
                        'MESP_07','MESP_08','MESP_09','MESP_10','MESP_11','MESP_12',
                        'MESC_01','MESC_02','MESC_03','MESC_04','MESC_05','MESC_06',
                        'MESC_07','MESC_08','MESC_09','MESC_10','MESC_11','MESC_12',
                        'TRIMP_01','TRIMP_02','TRIMP_03','TRIMP_04','TOTP_01','TOTP_02',
                        'TRIMC_01','TRIMC_02','TRIMC_03','TRIMC_04','TOTC_01','TOTC_02',
                        'TSEMP_01','TSEMP_02','TSEMC_01','TSEMC_02',   
                        'MES_P01','MES_P02','MES_P03','MES_P04','MES_P05','MES_P06',
                        'MES_P07','MES_P08','MES_P09','MES_P10','MES_P11','MES_P12',
                        'TRIM_P01','TRIM_P02','TRIM_P03','TRIM_P04','SEM_P01','SEM_P02','TOT_P01',
                        'SEMAF_01','SEMAF_02','SEMAF_03','SEMAF_04','SEMAF_05','SEMAF_06',
                        'SEMAF_07','SEMAF_08','SEMAF_09','SEMAF_10','SEMAF_11','SEMAF_12',
                        'SEMAFT_01','SEMAFT_02','SEMAFT_03','SEMAFT_04','SEMAFS_01','SEMAFS_02','SEMAFA_01',
                        'SOPORTE_ID','SOPORTE_01','SOPORTE_02','SOPORTE_03','SOPORTE_04','SOPORTE_05','SOPORTE_06',
                        'SOPORTE_07','SOPORTE_08','SOPORTE_09','SOPORTE_10','SOPORTE_11','SOPORTE_12',
                        'OBS_01','OBS_02',
                        'STATUS_1','STATUS_2','FECREG','FECREG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')               
                        ->where(['FOLIO' => $id, 'PARTIDA' => $id2])
                        //->where('FOLIO',$id)
                        //->where('PARTIDA',$id2)
                        ->first();  
        if($regprogdanual->count() <= 0){
            toastr()->error('No existen registros de acciones o metas del Programa anual.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }
        return view('sicinar.informelabores.editarSoportedet5',compact('nombre','usuario','regdepen','reganios','regperiodos','regmeses','regdias','regprogeanual','regprogdanual','regumedida','regepproy','regepprog'));
    }

    public function actionActualizarSoportedet5(soportedet5Request $request, $id, $id2){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango');
        $ip            = session()->get('ip');
        $arbol_id      = session()->get('arbol_id');    
        $depen_id      = session()->get('depen_id');                    

        // **************** actualizar ******************************
        $regprogdanual= regProgdAnualModel::where(['FOLIO' => $id, 'PARTIDA' => $id2]);
        if($regprogdanual->count() <= 0)
            toastr()->error('No existe acción o meta del programa anual.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            //********************** Actualizar ********************************/
            $name05 =null;
            if($request->hasFile('soporte_05')){
                $name05 = $request->periodo_id.'_'.$id.'_'.$id2.'_'.$request->file('soporte_05')->getClientOriginalName(); 
                $request->file('soporte_05')->move(public_path().'/storage/', $name05);

                $regprogdanual = regProgdAnualModel::where(['FOLIO' => $id, 'PARTIDA' => $id2])        
                                 ->update([                
                                           'SOPORTE_05' => $name05,

                                           'IP_M'       => $ip,
                                           'LOGIN_M'    => $nombre,
                                           'FECHA_M2'   => date('Y/m/d'),    //date('d/m/Y')
                                           'FECHA_M'    => date('Y/m/d')    //date('d/m/Y')                                
                                           ]);
                toastr()->success('Archivo digital justificación actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);

                /************ Bitacora inicia *************************************/ 
                setlocale(LC_TIME, "spanish");        
                $xip          = session()->get('ip');
                $xperiodo_id  = (int)date('Y');
                $xprograma_id = 1;
                $xmes_id      = (int)date('m');
                $xproceso_id  =         3;
                $xfuncion_id  =      3013;
                $xtrx_id      =        38;    //Actualizar 
                $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID', 'FUNCION_ID', 
                               'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                               ->where(['PERIODO_ID' => $xperiodo_id,  'MES_ID' => $xmes_id, 
                                        'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 
                                        'FOLIO' => $id])
                               ->get();
                if($regbitacora->count() <= 0){              // Alta
                    $nuevoregBitacora = new regBitacoraModel();              
                    $nuevoregBitacora->PERIODO_ID = $xperiodo_id;    // Año de transaccion 
                    $nuevoregBitacora->PROGRAMA_ID= $xprograma_id;   // Proyecto JAPEM 
                    $nuevoregBitacora->MES_ID     = $xmes_id;        // Mes de transaccion
                    $nuevoregBitacora->PROCESO_ID = $xproceso_id;    // Proceso de apoyo
                    $nuevoregBitacora->FUNCION_ID = $xfuncion_id;    // Funcion del modelado de procesos 
                    $nuevoregBitacora->TRX_ID     = $xtrx_id;        // Actividad del modelado de procesos
                    $nuevoregBitacora->FOLIO      = $id;             // Folio    
                    $nuevoregBitacora->NO_VECES   = 1;               // Numero de veces            
                    $nuevoregBitacora->IP         = $ip;             // IP
                    $nuevoregBitacora->LOGIN      = $nombre;         // Usuario 
                    $nuevoregBitacora->save();
                    if($nuevoregBitacora->save() == true)
                        toastr()->success('Trx de Archivo digital de justificación registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                    else
                        toastr()->error('Error en Trx de Archivo digital de justificación. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
                }else{                   
                    //*********** Obtine el no. de veces *****************************
                    $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id,'MES_ID'    => $xmes_id,
                                                          'PROCESO_ID' => $xproceso_id,'FUNCION_ID'=> $xfuncion_id, 
                                                          'TRX_ID'     => $xtrx_id    ,'FOLIO'     => $id])
                                 ->max('NO_VECES');
                    $xno_veces = $xno_veces+1;                        
                    //*********** Termina de obtener el no de veces *****************************         
                    $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                                   ->where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                            'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id, 'FOLIO'      => $id])
                                   ->update([
                                             'NO_VECES'=> $regbitacora->NO_VECES = $xno_veces,
                                             'IP_M'    => $regbitacora->IP       = $ip,
                                             'LOGIN_M' => $regbitacora->LOGIN_M  = $nombre,
                                             'FECHA_M' => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                             ]);
                    toastr()->success('Trx de Archivo digital de justificación actualizado en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                }   /************ Bitacora termina *************************************/       
            }       /************ Valida si viene vacio el arc. digital ****************/                              
        }           /************ Actualizar *******************************************/
        return redirect()->route('versoportesdet',$id);
    }    

   public function actionEditarSoportedet6($id,$id2){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $arbol_id     = session()->get('arbol_id');        
        $depen_id     = session()->get('depen_id');                

        $regumedida   = regUmedidaModel::select('UMED_ID','UMED_DESC')
                        ->orderBy('UMED_ID','asc')
                        ->get();      
        $regperiodos  = regPeriodosModel::select('PERIODO_ID', 'PERIODO_DESC')
                        ->get();         
        $reganios     = regAniosModel::select('ANIO_ID','ANIO_DESC')
                        ->get();        
        $regmeses     = regMesesModel::select('MES_ID','MES_DESC')
                        ->get();      
        $regdias      = regDiasModel::select('DIA_ID','DIA_DESC')
                        ->get();                                
        $regepprog    = regEPprogramaModel::select('EPPROG_ID','EPPROG_DESC')
                        ->orderBy('EPPROG_ID','asc')
                        ->get();                         
        $regepproy    = regEPproyectoModel::select('EPPROY_ID','EPPROY_DESC')
                        ->orderBy('EPPROY_ID','asc')
                        ->get();                
        //********* Validar rol de usuario **********************/
        if(session()->get('rango') !== '0'){                          
            $regdepen = regdepenModel::select('DEPEN_ID', 'DEPEN_DESC')
                        ->where('DEPEN_STATUS','1')
                        ->get();                                                         
        }else{
            $regdepen = regdepenModel::select('DEPEN_ID', 'DEPEN_DESC')
                        ->where('DEPEN_STATUS','1')
                        ->where('DEPEN_ID',$depen_id)
                        ->get();               
        }                    
        $regprogeanual= regProgeAnualModel::select('FOLIO','PERIODO_ID','DEPEN_ID1','DEPEN_ID2','DEPEN_ID3',
                        'EPPROG_ID','EPPROY_ID','FECHA_ENTREGA','FECHA_ENTREGA2','FECHA_ENTREGA3',
                        'PERIODO_ID1','MES_ID1','DIA_ID1','MES_ID2','PROGRAMA_ID','PROGRAMA_DESC',
                        'RESPONSABLE','ELABORO','AUTORIZO','OBS_1','OBS_2','STATUS_1','STATUS_2',
                        'FECREG','FECREG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')
                        ->where(  'FOLIO'     ,$id)
                        ->orderBy('PERIODO_ID','ASC')
                        ->orderBy('FOLIO'     ,'ASC')
                        ->get();
        $regprogdanual= regProgdAnualModel::select('FOLIO','PARTIDA','PERIODO_ID','CIPREP_ID','LGOB_COD',
                        'DEPEN_ID1','DEPEN_ID2','DEPEN_ID3','EPPROG_ID','EPPROY_ID',
                        'FECHA_ENTREGA','FECHA_ENTREGA2','FECHA_ENTREGA3','PERIODO_ID1','MES_ID1','DIA_ID1','MES_ID2',
                        'PROGRAMA_ID','PROGRAMA_DESC','ACTIVIDAD_ID','ACTIVIDAD_DESC','OBJETIVO_ID','OBJETIVO_DESC',
                        'OPERACIONAL_DESC','UMED_ID','MESP_01','MESP_02','MESP_03','MESP_04','MESP_05','MESP_06',
                        'MESP_07','MESP_08','MESP_09','MESP_10','MESP_11','MESP_12',
                        'MESC_01','MESC_02','MESC_03','MESC_04','MESC_05','MESC_06',
                        'MESC_07','MESC_08','MESC_09','MESC_10','MESC_11','MESC_12',
                        'TRIMP_01','TRIMP_02','TRIMP_03','TRIMP_04','TOTP_01','TOTP_02',
                        'TRIMC_01','TRIMC_02','TRIMC_03','TRIMC_04','TOTC_01','TOTC_02',
                        'TSEMP_01','TSEMP_02','TSEMC_01','TSEMC_02',   
                        'MES_P01','MES_P02','MES_P03','MES_P04','MES_P05','MES_P06',
                        'MES_P07','MES_P08','MES_P09','MES_P10','MES_P11','MES_P12',
                        'TRIM_P01','TRIM_P02','TRIM_P03','TRIM_P04','SEM_P01','SEM_P02','TOT_P01',
                        'SEMAF_01','SEMAF_02','SEMAF_03','SEMAF_04','SEMAF_05','SEMAF_06',
                        'SEMAF_07','SEMAF_08','SEMAF_09','SEMAF_10','SEMAF_11','SEMAF_12',
                        'SEMAFT_01','SEMAFT_02','SEMAFT_03','SEMAFT_04','SEMAFS_01','SEMAFS_02','SEMAFA_01',
                        'SOPORTE_ID','SOPORTE_01','SOPORTE_02','SOPORTE_03','SOPORTE_04','SOPORTE_05','SOPORTE_06',
                        'SOPORTE_07','SOPORTE_08','SOPORTE_09','SOPORTE_10','SOPORTE_11','SOPORTE_12',
                        'OBS_01','OBS_02',
                        'STATUS_1','STATUS_2','FECREG','FECREG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')               
                        ->where(['FOLIO' => $id, 'PARTIDA' => $id2])
                        //->where('FOLIO',$id)
                        //->where('PARTIDA',$id2)
                        ->first();  
        if($regprogdanual->count() <= 0){
            toastr()->error('No existen registros de acciones o metas del Programa anual.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }
        return view('sicinar.informelabores.editarSoportedet6',compact('nombre','usuario','regdepen','reganios','regperiodos','regmeses','regdias','regprogeanual','regprogdanual','regumedida','regepproy','regepprog'));
    }

    public function actionActualizarSoportedet6(soportedet6Request $request, $id, $id2){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango');
        $ip            = session()->get('ip');
        $arbol_id      = session()->get('arbol_id');    
        $depen_id      = session()->get('depen_id');                    

        // **************** actualizar ******************************
        $regprogdanual= regProgdAnualModel::where(['FOLIO' => $id, 'PARTIDA' => $id2]);
        if($regprogdanual->count() <= 0)
            toastr()->error('No existe acción o meta del programa anual.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            //********************** Actualizar ********************************/
            $name06 =null;
            if($request->hasFile('soporte_06')){
                $name06 = $request->periodo_id.'_'.$id.'_'.$id2.'_'.$request->file('soporte_06')->getClientOriginalName(); 
                $request->file('soporte_06')->move(public_path().'/storage/', $name06);

                $regprogdanual = regProgdAnualModel::where(['FOLIO' => $id, 'PARTIDA' => $id2])        
                                 ->update([                
                                           'SOPORTE_06' => $name06,

                                           'IP_M'       => $ip,
                                           'LOGIN_M'    => $nombre,
                                           'FECHA_M2'   => date('Y/m/d'),    //date('d/m/Y')
                                           'FECHA_M'    => date('Y/m/d')    //date('d/m/Y')                                
                                           ]);
                toastr()->success('Archivo digital justificación actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);

                /************ Bitacora inicia *************************************/ 
                setlocale(LC_TIME, "spanish");        
                $xip          = session()->get('ip');
                $xperiodo_id  = (int)date('Y');
                $xprograma_id = 1;
                $xmes_id      = (int)date('m');
                $xproceso_id  =         3;
                $xfuncion_id  =      3013;
                $xtrx_id      =        38;    //Actualizar 
                $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID', 'FUNCION_ID', 
                               'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                               ->where(['PERIODO_ID' => $xperiodo_id,  'MES_ID' => $xmes_id, 
                                        'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 
                                        'FOLIO' => $id])
                               ->get();
                if($regbitacora->count() <= 0){              // Alta
                    $nuevoregBitacora = new regBitacoraModel();              
                    $nuevoregBitacora->PERIODO_ID = $xperiodo_id;    // Año de transaccion 
                    $nuevoregBitacora->PROGRAMA_ID= $xprograma_id;   // Proyecto JAPEM 
                    $nuevoregBitacora->MES_ID     = $xmes_id;        // Mes de transaccion
                    $nuevoregBitacora->PROCESO_ID = $xproceso_id;    // Proceso de apoyo
                    $nuevoregBitacora->FUNCION_ID = $xfuncion_id;    // Funcion del modelado de procesos 
                    $nuevoregBitacora->TRX_ID     = $xtrx_id;        // Actividad del modelado de procesos
                    $nuevoregBitacora->FOLIO      = $id;             // Folio    
                    $nuevoregBitacora->NO_VECES   = 1;               // Numero de veces            
                    $nuevoregBitacora->IP         = $ip;             // IP
                    $nuevoregBitacora->LOGIN      = $nombre;         // Usuario 
                    $nuevoregBitacora->save();
                    if($nuevoregBitacora->save() == true)
                        toastr()->success('Trx de Archivo digital de justificación registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                    else
                        toastr()->error('Error en Trx de Archivo digital de justificación. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
                }else{                   
                    //*********** Obtine el no. de veces *****************************
                    $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id,'MES_ID'    => $xmes_id,
                                                          'PROCESO_ID' => $xproceso_id,'FUNCION_ID'=> $xfuncion_id, 
                                                          'TRX_ID'     => $xtrx_id    ,'FOLIO'     => $id])
                                 ->max('NO_VECES');
                    $xno_veces = $xno_veces+1;                        
                    //*********** Termina de obtener el no de veces *****************************         
                    $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                                   ->where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                            'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id, 'FOLIO'      => $id])
                                   ->update([
                                             'NO_VECES'=> $regbitacora->NO_VECES = $xno_veces,
                                             'IP_M'    => $regbitacora->IP       = $ip,
                                             'LOGIN_M' => $regbitacora->LOGIN_M  = $nombre,
                                             'FECHA_M' => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                             ]);
                    toastr()->success('Trx de Archivo digital de justificación actualizado en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                }   /************ Bitacora termina *************************************/       
            }       /************ Valida si viene vacio el arc. digital ****************/                              
        }           /************ Actualizar *******************************************/
        return redirect()->route('versoportesdet',$id);
    }    

   public function actionEditarSoportedet7($id,$id2){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $arbol_id     = session()->get('arbol_id');        
        $depen_id     = session()->get('depen_id');                

        $regumedida   = regUmedidaModel::select('UMED_ID','UMED_DESC')
                        ->orderBy('UMED_ID','asc')
                        ->get();      
        $regperiodos  = regPeriodosModel::select('PERIODO_ID', 'PERIODO_DESC')
                        ->get();         
        $reganios     = regAniosModel::select('ANIO_ID','ANIO_DESC')
                        ->get();        
        $regmeses     = regMesesModel::select('MES_ID','MES_DESC')
                        ->get();      
        $regdias      = regDiasModel::select('DIA_ID','DIA_DESC')
                        ->get();                                
        $regepprog    = regEPprogramaModel::select('EPPROG_ID','EPPROG_DESC')
                        ->orderBy('EPPROG_ID','asc')
                        ->get();                         
        $regepproy    = regEPproyectoModel::select('EPPROY_ID','EPPROY_DESC')
                        ->orderBy('EPPROY_ID','asc')
                        ->get();                
        //********* Validar rol de usuario **********************/
        if(session()->get('rango') !== '0'){                          
            $regdepen = regdepenModel::select('DEPEN_ID', 'DEPEN_DESC')
                        ->where('DEPEN_STATUS','1')
                        ->get();                                                         
        }else{
            $regdepen = regdepenModel::select('DEPEN_ID', 'DEPEN_DESC')
                        ->where('DEPEN_STATUS','1')
                        ->where('DEPEN_ID',$depen_id)
                        ->get();               
        }                    
        $regprogeanual= regProgeAnualModel::select('FOLIO','PERIODO_ID','DEPEN_ID1','DEPEN_ID2','DEPEN_ID3',
                        'EPPROG_ID','EPPROY_ID','FECHA_ENTREGA','FECHA_ENTREGA2','FECHA_ENTREGA3',
                        'PERIODO_ID1','MES_ID1','DIA_ID1','MES_ID2','PROGRAMA_ID','PROGRAMA_DESC',
                        'RESPONSABLE','ELABORO','AUTORIZO','OBS_1','OBS_2','STATUS_1','STATUS_2',
                        'FECREG','FECREG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')
                        ->where(  'FOLIO'     ,$id)
                        ->orderBy('PERIODO_ID','ASC')
                        ->orderBy('FOLIO'     ,'ASC')
                        ->get();
        $regprogdanual= regProgdAnualModel::select('FOLIO','PARTIDA','PERIODO_ID','CIPREP_ID','LGOB_COD',
                        'DEPEN_ID1','DEPEN_ID2','DEPEN_ID3','EPPROG_ID','EPPROY_ID',
                        'FECHA_ENTREGA','FECHA_ENTREGA2','FECHA_ENTREGA3','PERIODO_ID1','MES_ID1','DIA_ID1','MES_ID2',
                        'PROGRAMA_ID','PROGRAMA_DESC','ACTIVIDAD_ID','ACTIVIDAD_DESC','OBJETIVO_ID','OBJETIVO_DESC',
                        'OPERACIONAL_DESC','UMED_ID','MESP_01','MESP_02','MESP_03','MESP_04','MESP_05','MESP_06',
                        'MESP_07','MESP_08','MESP_09','MESP_10','MESP_11','MESP_12',
                        'MESC_01','MESC_02','MESC_03','MESC_04','MESC_05','MESC_06',
                        'MESC_07','MESC_08','MESC_09','MESC_10','MESC_11','MESC_12',
                        'TRIMP_01','TRIMP_02','TRIMP_03','TRIMP_04','TOTP_01','TOTP_02',
                        'TRIMC_01','TRIMC_02','TRIMC_03','TRIMC_04','TOTC_01','TOTC_02',
                        'TSEMP_01','TSEMP_02','TSEMC_01','TSEMC_02',   
                        'MES_P01','MES_P02','MES_P03','MES_P04','MES_P05','MES_P06',
                        'MES_P07','MES_P08','MES_P09','MES_P10','MES_P11','MES_P12',
                        'TRIM_P01','TRIM_P02','TRIM_P03','TRIM_P04','SEM_P01','SEM_P02','TOT_P01',
                        'SEMAF_01','SEMAF_02','SEMAF_03','SEMAF_04','SEMAF_05','SEMAF_06',
                        'SEMAF_07','SEMAF_08','SEMAF_09','SEMAF_10','SEMAF_11','SEMAF_12',
                        'SEMAFT_01','SEMAFT_02','SEMAFT_03','SEMAFT_04','SEMAFS_01','SEMAFS_02','SEMAFA_01',
                        'SOPORTE_ID','SOPORTE_01','SOPORTE_02','SOPORTE_03','SOPORTE_04','SOPORTE_05','SOPORTE_06',
                        'SOPORTE_07','SOPORTE_08','SOPORTE_09','SOPORTE_10','SOPORTE_11','SOPORTE_12',
                        'OBS_01','OBS_02',
                        'STATUS_1','STATUS_2','FECREG','FECREG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')               
                        ->where(['FOLIO' => $id, 'PARTIDA' => $id2])
                        //->where('FOLIO',$id)
                        //->where('PARTIDA',$id2)
                        ->first();  
        if($regprogdanual->count() <= 0){
            toastr()->error('No existen registros de acciones o metas del Programa anual.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }
        return view('sicinar.informelabores.editarSoportedet7',compact('nombre','usuario','regdepen','reganios','regperiodos','regmeses','regdias','regprogeanual','regprogdanual','regumedida','regepproy','regepprog'));
    }

    public function actionActualizarSoportedet7(soportedet7Request $request, $id, $id2){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango');
        $ip            = session()->get('ip');
        $arbol_id      = session()->get('arbol_id');    
        $depen_id      = session()->get('depen_id');                    

        // **************** actualizar ******************************
        $regprogdanual= regProgdAnualModel::where(['FOLIO' => $id, 'PARTIDA' => $id2]);
        if($regprogdanual->count() <= 0)
            toastr()->error('No existe acción o meta del programa anual.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            //********************** Actualizar ********************************/
            $name07 =null;
            if($request->hasFile('soporte_07')){
                $name07 = $request->periodo_id.'_'.$id.'_'.$id2.'_'.$request->file('soporte_07')->getClientOriginalName(); 
                $request->file('soporte_07')->move(public_path().'/storage/', $name07);

                $regprogdanual = regProgdAnualModel::where(['FOLIO' => $id, 'PARTIDA' => $id2])        
                                 ->update([                
                                           'SOPORTE_07' => $name07,

                                           'IP_M'       => $ip,
                                           'LOGIN_M'    => $nombre,
                                           'FECHA_M2'   => date('Y/m/d'),    //date('d/m/Y')
                                           'FECHA_M'    => date('Y/m/d')    //date('d/m/Y')                                
                                           ]);
                toastr()->success('Archivo digital justificación actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);

                /************ Bitacora inicia *************************************/ 
                setlocale(LC_TIME, "spanish");        
                $xip          = session()->get('ip');
                $xperiodo_id  = (int)date('Y');
                $xprograma_id = 1;
                $xmes_id      = (int)date('m');
                $xproceso_id  =         3;
                $xfuncion_id  =      3013;
                $xtrx_id      =        38;    //Actualizar 
                $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID', 'FUNCION_ID', 
                               'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                               ->where(['PERIODO_ID' => $xperiodo_id,  'MES_ID' => $xmes_id, 
                                        'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 
                                        'FOLIO' => $id])
                               ->get();
                if($regbitacora->count() <= 0){              // Alta
                    $nuevoregBitacora = new regBitacoraModel();              
                    $nuevoregBitacora->PERIODO_ID = $xperiodo_id;    // Año de transaccion 
                    $nuevoregBitacora->PROGRAMA_ID= $xprograma_id;   // Proyecto JAPEM 
                    $nuevoregBitacora->MES_ID     = $xmes_id;        // Mes de transaccion
                    $nuevoregBitacora->PROCESO_ID = $xproceso_id;    // Proceso de apoyo
                    $nuevoregBitacora->FUNCION_ID = $xfuncion_id;    // Funcion del modelado de procesos 
                    $nuevoregBitacora->TRX_ID     = $xtrx_id;        // Actividad del modelado de procesos
                    $nuevoregBitacora->FOLIO      = $id;             // Folio    
                    $nuevoregBitacora->NO_VECES   = 1;               // Numero de veces            
                    $nuevoregBitacora->IP         = $ip;             // IP
                    $nuevoregBitacora->LOGIN      = $nombre;         // Usuario 
                    $nuevoregBitacora->save();
                    if($nuevoregBitacora->save() == true)
                        toastr()->success('Trx de Archivo digital de justificación registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                    else
                        toastr()->error('Error en Trx de Archivo digital de justificación. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
                }else{                   
                    //*********** Obtine el no. de veces *****************************
                    $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id,'MES_ID'    => $xmes_id,
                                                          'PROCESO_ID' => $xproceso_id,'FUNCION_ID'=> $xfuncion_id, 
                                                          'TRX_ID'     => $xtrx_id    ,'FOLIO'     => $id])
                                 ->max('NO_VECES');
                    $xno_veces = $xno_veces+1;                        
                    //*********** Termina de obtener el no de veces *****************************         
                    $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                                   ->where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                            'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id, 'FOLIO'      => $id])
                                   ->update([
                                             'NO_VECES'=> $regbitacora->NO_VECES = $xno_veces,
                                             'IP_M'    => $regbitacora->IP       = $ip,
                                             'LOGIN_M' => $regbitacora->LOGIN_M  = $nombre,
                                             'FECHA_M' => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                             ]);
                    toastr()->success('Trx de Archivo digital de justificación actualizado en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                }   /************ Bitacora termina *************************************/       
            }       /************ Valida si viene vacio el arc. digital ****************/                              
        }           /************ Actualizar *******************************************/
        return redirect()->route('versoportesdet',$id);
    }    

   public function actionEditarSoportedet8($id,$id2){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $arbol_id     = session()->get('arbol_id');        
        $depen_id     = session()->get('depen_id');                

        $regumedida   = regUmedidaModel::select('UMED_ID','UMED_DESC')
                        ->orderBy('UMED_ID','asc')
                        ->get();      
        $regperiodos  = regPeriodosModel::select('PERIODO_ID', 'PERIODO_DESC')
                        ->get();         
        $reganios     = regAniosModel::select('ANIO_ID','ANIO_DESC')
                        ->get();        
        $regmeses     = regMesesModel::select('MES_ID','MES_DESC')
                        ->get();      
        $regdias      = regDiasModel::select('DIA_ID','DIA_DESC')
                        ->get();                                
        $regepprog    = regEPprogramaModel::select('EPPROG_ID','EPPROG_DESC')
                        ->orderBy('EPPROG_ID','asc')
                        ->get();                         
        $regepproy    = regEPproyectoModel::select('EPPROY_ID','EPPROY_DESC')
                        ->orderBy('EPPROY_ID','asc')
                        ->get();                
        //********* Validar rol de usuario **********************/
        if(session()->get('rango') !== '0'){                          
            $regdepen = regdepenModel::select('DEPEN_ID', 'DEPEN_DESC')
                        ->where('DEPEN_STATUS','1')
                        ->get();                                                         
        }else{
            $regdepen = regdepenModel::select('DEPEN_ID', 'DEPEN_DESC')
                        ->where('DEPEN_STATUS','1')
                        ->where('DEPEN_ID',$depen_id)
                        ->get();               
        }                    
        $regprogeanual= regProgeAnualModel::select('FOLIO','PERIODO_ID','DEPEN_ID1','DEPEN_ID2','DEPEN_ID3',
                        'EPPROG_ID','EPPROY_ID','FECHA_ENTREGA','FECHA_ENTREGA2','FECHA_ENTREGA3',
                        'PERIODO_ID1','MES_ID1','DIA_ID1','MES_ID2','PROGRAMA_ID','PROGRAMA_DESC',
                        'RESPONSABLE','ELABORO','AUTORIZO','OBS_1','OBS_2','STATUS_1','STATUS_2',
                        'FECREG','FECREG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')
                        ->where(  'FOLIO'     ,$id)
                        ->orderBy('PERIODO_ID','ASC')
                        ->orderBy('FOLIO'     ,'ASC')
                        ->get();
        $regprogdanual= regProgdAnualModel::select('FOLIO','PARTIDA','PERIODO_ID','CIPREP_ID','LGOB_COD',
                        'DEPEN_ID1','DEPEN_ID2','DEPEN_ID3','EPPROG_ID','EPPROY_ID',
                        'FECHA_ENTREGA','FECHA_ENTREGA2','FECHA_ENTREGA3','PERIODO_ID1','MES_ID1','DIA_ID1','MES_ID2',
                        'PROGRAMA_ID','PROGRAMA_DESC','ACTIVIDAD_ID','ACTIVIDAD_DESC','OBJETIVO_ID','OBJETIVO_DESC',
                        'OPERACIONAL_DESC','UMED_ID','MESP_01','MESP_02','MESP_03','MESP_04','MESP_05','MESP_06',
                        'MESP_07','MESP_08','MESP_09','MESP_10','MESP_11','MESP_12',
                        'MESC_01','MESC_02','MESC_03','MESC_04','MESC_05','MESC_06',
                        'MESC_07','MESC_08','MESC_09','MESC_10','MESC_11','MESC_12',
                        'TRIMP_01','TRIMP_02','TRIMP_03','TRIMP_04','TOTP_01','TOTP_02',
                        'TRIMC_01','TRIMC_02','TRIMC_03','TRIMC_04','TOTC_01','TOTC_02',
                        'TSEMP_01','TSEMP_02','TSEMC_01','TSEMC_02',   
                        'MES_P01','MES_P02','MES_P03','MES_P04','MES_P05','MES_P06',
                        'MES_P07','MES_P08','MES_P09','MES_P10','MES_P11','MES_P12',
                        'TRIM_P01','TRIM_P02','TRIM_P03','TRIM_P04','SEM_P01','SEM_P02','TOT_P01',
                        'SEMAF_01','SEMAF_02','SEMAF_03','SEMAF_04','SEMAF_05','SEMAF_06',
                        'SEMAF_07','SEMAF_08','SEMAF_09','SEMAF_10','SEMAF_11','SEMAF_12',
                        'SEMAFT_01','SEMAFT_02','SEMAFT_03','SEMAFT_04','SEMAFS_01','SEMAFS_02','SEMAFA_01',
                        'SOPORTE_ID','SOPORTE_01','SOPORTE_02','SOPORTE_03','SOPORTE_04','SOPORTE_05','SOPORTE_06',
                        'SOPORTE_07','SOPORTE_08','SOPORTE_09','SOPORTE_10','SOPORTE_11','SOPORTE_12',
                        'OBS_01','OBS_02',
                        'STATUS_1','STATUS_2','FECREG','FECREG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')               
                        ->where(['FOLIO' => $id, 'PARTIDA' => $id2])
                        //->where('FOLIO',$id)
                        //->where('PARTIDA',$id2)
                        ->first();  
        if($regprogdanual->count() <= 0){
            toastr()->error('No existen registros de acciones o metas del Programa anual.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }
        return view('sicinar.informelabores.editarSoportedet8',compact('nombre','usuario','regdepen','reganios','regperiodos','regmeses','regdias','regprogeanual','regprogdanual','regumedida','regepproy','regepprog'));
    }

    public function actionActualizarSoportedet8(soportedet8Request $request, $id, $id2){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango');
        $ip            = session()->get('ip');
        $arbol_id      = session()->get('arbol_id');    
        $depen_id      = session()->get('depen_id');                    

        // **************** actualizar ******************************
        $regprogdanual= regProgdAnualModel::where(['FOLIO' => $id, 'PARTIDA' => $id2]);
        if($regprogdanual->count() <= 0)
            toastr()->error('No existe acción o meta del programa anual.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            //********************** Actualizar ********************************/
            $name08 =null;
            if($request->hasFile('soporte_08')){
                $name08 = $request->periodo_id.'_'.$id.'_'.$id2.'_'.$request->file('soporte_08')->getClientOriginalName(); 
                $request->file('soporte_08')->move(public_path().'/storage/', $name08);

                $regprogdanual = regProgdAnualModel::where(['FOLIO' => $id, 'PARTIDA' => $id2])        
                                 ->update([                
                                           'SOPORTE_08' => $name08,

                                           'IP_M'       => $ip,
                                           'LOGIN_M'    => $nombre,
                                           'FECHA_M2'   => date('Y/m/d'),    //date('d/m/Y')
                                           'FECHA_M'    => date('Y/m/d')    //date('d/m/Y')                                
                                           ]);
                toastr()->success('Archivo digital justificación actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);

                /************ Bitacora inicia *************************************/ 
                setlocale(LC_TIME, "spanish");        
                $xip          = session()->get('ip');
                $xperiodo_id  = (int)date('Y');
                $xprograma_id = 1;
                $xmes_id      = (int)date('m');
                $xproceso_id  =         3;
                $xfuncion_id  =      3013;
                $xtrx_id      =        38;    //Actualizar 
                $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID', 'FUNCION_ID', 
                               'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                               ->where(['PERIODO_ID' => $xperiodo_id,  'MES_ID' => $xmes_id, 
                                        'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 
                                        'FOLIO' => $id])
                               ->get();
                if($regbitacora->count() <= 0){              // Alta
                    $nuevoregBitacora = new regBitacoraModel();              
                    $nuevoregBitacora->PERIODO_ID = $xperiodo_id;    // Año de transaccion 
                    $nuevoregBitacora->PROGRAMA_ID= $xprograma_id;   // Proyecto JAPEM 
                    $nuevoregBitacora->MES_ID     = $xmes_id;        // Mes de transaccion
                    $nuevoregBitacora->PROCESO_ID = $xproceso_id;    // Proceso de apoyo
                    $nuevoregBitacora->FUNCION_ID = $xfuncion_id;    // Funcion del modelado de procesos 
                    $nuevoregBitacora->TRX_ID     = $xtrx_id;        // Actividad del modelado de procesos
                    $nuevoregBitacora->FOLIO      = $id;             // Folio    
                    $nuevoregBitacora->NO_VECES   = 1;               // Numero de veces            
                    $nuevoregBitacora->IP         = $ip;             // IP
                    $nuevoregBitacora->LOGIN      = $nombre;         // Usuario 
                    $nuevoregBitacora->save();
                    if($nuevoregBitacora->save() == true)
                        toastr()->success('Trx de Archivo digital de justificación registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                    else
                        toastr()->error('Error en Trx de Archivo digital de justificación. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
                }else{                   
                    //*********** Obtine el no. de veces *****************************
                    $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id,'MES_ID'    => $xmes_id,
                                                          'PROCESO_ID' => $xproceso_id,'FUNCION_ID'=> $xfuncion_id, 
                                                          'TRX_ID'     => $xtrx_id    ,'FOLIO'     => $id])
                                 ->max('NO_VECES');
                    $xno_veces = $xno_veces+1;                        
                    //*********** Termina de obtener el no de veces *****************************         
                    $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                                   ->where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                            'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id, 'FOLIO'      => $id])
                                   ->update([
                                             'NO_VECES'=> $regbitacora->NO_VECES = $xno_veces,
                                             'IP_M'    => $regbitacora->IP       = $ip,
                                             'LOGIN_M' => $regbitacora->LOGIN_M  = $nombre,
                                             'FECHA_M' => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                             ]);
                    toastr()->success('Trx de Archivo digital de justificación actualizado en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                }   /************ Bitacora termina *************************************/       
            }       /************ Valida si viene vacio el arc. digital ****************/                              
        }           /************ Actualizar *******************************************/
        return redirect()->route('versoportesdet',$id);
    }    

    public function actionEditarSoportedet9($id,$id2){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $arbol_id     = session()->get('arbol_id');        
        $depen_id     = session()->get('depen_id');                

        $regumedida   = regUmedidaModel::select('UMED_ID','UMED_DESC')
                        ->orderBy('UMED_ID','asc')
                        ->get();      
        $regperiodos  = regPeriodosModel::select('PERIODO_ID', 'PERIODO_DESC')
                        ->get();         
        $reganios     = regAniosModel::select('ANIO_ID','ANIO_DESC')
                        ->get();        
        $regmeses     = regMesesModel::select('MES_ID','MES_DESC')
                        ->get();      
        $regdias      = regDiasModel::select('DIA_ID','DIA_DESC')
                        ->get();                                
        $regepprog    = regEPprogramaModel::select('EPPROG_ID','EPPROG_DESC')
                        ->orderBy('EPPROG_ID','asc')
                        ->get();                         
        $regepproy    = regEPproyectoModel::select('EPPROY_ID','EPPROY_DESC')
                        ->orderBy('EPPROY_ID','asc')
                        ->get();                
        //********* Validar rol de usuario **********************/
        if(session()->get('rango') !== '0'){                          
            $regdepen = regdepenModel::select('DEPEN_ID', 'DEPEN_DESC')
                        ->where('DEPEN_STATUS','1')
                        ->get();                                                         
        }else{
            $regdepen = regdepenModel::select('DEPEN_ID', 'DEPEN_DESC')
                        ->where('DEPEN_STATUS','1')
                        ->where('DEPEN_ID',$depen_id)
                        ->get();               
        }                    
        $regprogeanual= regProgeAnualModel::select('FOLIO','PERIODO_ID','DEPEN_ID1','DEPEN_ID2','DEPEN_ID3',
                        'EPPROG_ID','EPPROY_ID','FECHA_ENTREGA','FECHA_ENTREGA2','FECHA_ENTREGA3',
                        'PERIODO_ID1','MES_ID1','DIA_ID1','MES_ID2','PROGRAMA_ID','PROGRAMA_DESC',
                        'RESPONSABLE','ELABORO','AUTORIZO','OBS_1','OBS_2','STATUS_1','STATUS_2',
                        'FECREG','FECREG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')
                        ->where(  'FOLIO'     ,$id)
                        ->orderBy('PERIODO_ID','ASC')
                        ->orderBy('FOLIO'     ,'ASC')
                        ->get();
        $regprogdanual= regProgdAnualModel::select('FOLIO','PARTIDA','PERIODO_ID','CIPREP_ID','LGOB_COD',
                        'DEPEN_ID1','DEPEN_ID2','DEPEN_ID3','EPPROG_ID','EPPROY_ID',
                        'FECHA_ENTREGA','FECHA_ENTREGA2','FECHA_ENTREGA3','PERIODO_ID1','MES_ID1','DIA_ID1','MES_ID2',
                        'PROGRAMA_ID','PROGRAMA_DESC','ACTIVIDAD_ID','ACTIVIDAD_DESC','OBJETIVO_ID','OBJETIVO_DESC',
                        'OPERACIONAL_DESC','UMED_ID','MESP_01','MESP_02','MESP_03','MESP_04','MESP_05','MESP_06',
                        'MESP_07','MESP_08','MESP_09','MESP_10','MESP_11','MESP_12',
                        'MESC_01','MESC_02','MESC_03','MESC_04','MESC_05','MESC_06',
                        'MESC_07','MESC_08','MESC_09','MESC_10','MESC_11','MESC_12',
                        'TRIMP_01','TRIMP_02','TRIMP_03','TRIMP_04','TOTP_01','TOTP_02',
                        'TRIMC_01','TRIMC_02','TRIMC_03','TRIMC_04','TOTC_01','TOTC_02',
                        'TSEMP_01','TSEMP_02','TSEMC_01','TSEMC_02',   
                        'MES_P01','MES_P02','MES_P03','MES_P04','MES_P05','MES_P06',
                        'MES_P07','MES_P08','MES_P09','MES_P10','MES_P11','MES_P12',
                        'TRIM_P01','TRIM_P02','TRIM_P03','TRIM_P04','SEM_P01','SEM_P02','TOT_P01',
                        'SEMAF_01','SEMAF_02','SEMAF_03','SEMAF_04','SEMAF_05','SEMAF_06',
                        'SEMAF_07','SEMAF_08','SEMAF_09','SEMAF_10','SEMAF_11','SEMAF_12',
                        'SEMAFT_01','SEMAFT_02','SEMAFT_03','SEMAFT_04','SEMAFS_01','SEMAFS_02','SEMAFA_01',
                        'SOPORTE_ID','SOPORTE_01','SOPORTE_02','SOPORTE_03','SOPORTE_04','SOPORTE_05','SOPORTE_06',
                        'SOPORTE_07','SOPORTE_08','SOPORTE_09','SOPORTE_10','SOPORTE_11','SOPORTE_12',
                        'OBS_01','OBS_02',
                        'STATUS_1','STATUS_2','FECREG','FECREG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')               
                        ->where(['FOLIO' => $id, 'PARTIDA' => $id2])
                        //->where('FOLIO',$id)
                        //->where('PARTIDA',$id2)
                        ->first();  
        if($regprogdanual->count() <= 0){
            toastr()->error('No existen registros de acciones o metas del Programa anual.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }
        return view('sicinar.informelabores.editarSoportedet9',compact('nombre','usuario','regdepen','reganios','regperiodos','regmeses','regdias','regprogeanual','regprogdanual','regumedida','regepproy','regepprog'));
    }

    public function actionActualizarSoportedet9(soportedet9Request $request, $id, $id2){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango');
        $ip            = session()->get('ip');
        $arbol_id      = session()->get('arbol_id');    
        $depen_id      = session()->get('depen_id');                    

        // **************** actualizar ******************************
        $regprogdanual= regProgdAnualModel::where(['FOLIO' => $id, 'PARTIDA' => $id2]);
        if($regprogdanual->count() <= 0)
            toastr()->error('No existe acción o meta del programa anual.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            //********************** Actualizar ********************************/
            $name09 =null;
            if($request->hasFile('soporte_09')){
                $name09 = $request->periodo_id.'_'.$id.'_'.$id2.'_'.$request->file('soporte_09')->getClientOriginalName(); 
                $request->file('soporte_09')->move(public_path().'/storage/', $name09);

                $regprogdanual = regProgdAnualModel::where(['FOLIO' => $id, 'PARTIDA' => $id2])        
                                 ->update([                
                                           'SOPORTE_09' => $name09,

                                           'IP_M'       => $ip,
                                           'LOGIN_M'    => $nombre,
                                           'FECHA_M2'   => date('Y/m/d'),    //date('d/m/Y')
                                           'FECHA_M'    => date('Y/m/d')    //date('d/m/Y')                                
                                           ]);
                toastr()->success('Archivo digital justificación actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);

                /************ Bitacora inicia *************************************/ 
                setlocale(LC_TIME, "spanish");        
                $xip          = session()->get('ip');
                $xperiodo_id  = (int)date('Y');
                $xprograma_id = 1;
                $xmes_id      = (int)date('m');
                $xproceso_id  =         3;
                $xfuncion_id  =      3013;
                $xtrx_id      =        38;    //Actualizar 
                $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID', 'FUNCION_ID', 
                               'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                               ->where(['PERIODO_ID' => $xperiodo_id,  'MES_ID' => $xmes_id, 
                                        'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 
                                        'FOLIO' => $id])
                               ->get();
                if($regbitacora->count() <= 0){              // Alta
                    $nuevoregBitacora = new regBitacoraModel();              
                    $nuevoregBitacora->PERIODO_ID = $xperiodo_id;    // Año de transaccion 
                    $nuevoregBitacora->PROGRAMA_ID= $xprograma_id;   // Proyecto JAPEM 
                    $nuevoregBitacora->MES_ID     = $xmes_id;        // Mes de transaccion
                    $nuevoregBitacora->PROCESO_ID = $xproceso_id;    // Proceso de apoyo
                    $nuevoregBitacora->FUNCION_ID = $xfuncion_id;    // Funcion del modelado de procesos 
                    $nuevoregBitacora->TRX_ID     = $xtrx_id;        // Actividad del modelado de procesos
                    $nuevoregBitacora->FOLIO      = $id;             // Folio    
                    $nuevoregBitacora->NO_VECES   = 1;               // Numero de veces            
                    $nuevoregBitacora->IP         = $ip;             // IP
                    $nuevoregBitacora->LOGIN      = $nombre;         // Usuario 
                    $nuevoregBitacora->save();
                    if($nuevoregBitacora->save() == true)
                        toastr()->success('Trx de Archivo digital de justificación registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                    else
                        toastr()->error('Error en Trx de Archivo digital de justificación. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
                }else{                   
                    //*********** Obtine el no. de veces *****************************
                    $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id,'MES_ID'    => $xmes_id,
                                                          'PROCESO_ID' => $xproceso_id,'FUNCION_ID'=> $xfuncion_id, 
                                                          'TRX_ID'     => $xtrx_id    ,'FOLIO'     => $id])
                                 ->max('NO_VECES');
                    $xno_veces = $xno_veces+1;                        
                    //*********** Termina de obtener el no de veces *****************************         
                    $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                                   ->where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                            'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id, 'FOLIO'      => $id])
                                   ->update([
                                             'NO_VECES'=> $regbitacora->NO_VECES = $xno_veces,
                                             'IP_M'    => $regbitacora->IP       = $ip,
                                             'LOGIN_M' => $regbitacora->LOGIN_M  = $nombre,
                                             'FECHA_M' => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                             ]);
                    toastr()->success('Trx de Archivo digital de justificación actualizado en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                }   /************ Bitacora termina *************************************/       
            }       /************ Valida si viene vacio el arc. digital ****************/                              
        }           /************ Actualizar *******************************************/
        return redirect()->route('versoportesdet',$id);
    }   

    public function actionEditarSoportedet10($id,$id2){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $arbol_id     = session()->get('arbol_id');        
        $depen_id     = session()->get('depen_id');                

        $regumedida   = regUmedidaModel::select('UMED_ID','UMED_DESC')
                        ->orderBy('UMED_ID','asc')
                        ->get();      
        $regperiodos  = regPeriodosModel::select('PERIODO_ID', 'PERIODO_DESC')
                        ->get();         
        $reganios     = regAniosModel::select('ANIO_ID','ANIO_DESC')
                        ->get();        
        $regmeses     = regMesesModel::select('MES_ID','MES_DESC')
                        ->get();      
        $regdias      = regDiasModel::select('DIA_ID','DIA_DESC')
                        ->get();                                
        $regepprog    = regEPprogramaModel::select('EPPROG_ID','EPPROG_DESC')
                        ->orderBy('EPPROG_ID','asc')
                        ->get();                         
        $regepproy    = regEPproyectoModel::select('EPPROY_ID','EPPROY_DESC')
                        ->orderBy('EPPROY_ID','asc')
                        ->get();                
        //********* Validar rol de usuario **********************/
        if(session()->get('rango') !== '0'){                          
            $regdepen = regdepenModel::select('DEPEN_ID', 'DEPEN_DESC')
                        ->where('DEPEN_STATUS','1')
                        ->get();                                                         
        }else{
            $regdepen = regdepenModel::select('DEPEN_ID', 'DEPEN_DESC')
                        ->where('DEPEN_STATUS','1')
                        ->where('DEPEN_ID',$depen_id)
                        ->get();               
        }                    
        $regprogeanual= regProgeAnualModel::select('FOLIO','PERIODO_ID','DEPEN_ID1','DEPEN_ID2','DEPEN_ID3',
                        'EPPROG_ID','EPPROY_ID','FECHA_ENTREGA','FECHA_ENTREGA2','FECHA_ENTREGA3',
                        'PERIODO_ID1','MES_ID1','DIA_ID1','MES_ID2','PROGRAMA_ID','PROGRAMA_DESC',
                        'RESPONSABLE','ELABORO','AUTORIZO','OBS_1','OBS_2','STATUS_1','STATUS_2',
                        'FECREG','FECREG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')
                        ->where(  'FOLIO'     ,$id)
                        ->orderBy('PERIODO_ID','ASC')
                        ->orderBy('FOLIO'     ,'ASC')
                        ->get();
        $regprogdanual= regProgdAnualModel::select('FOLIO','PARTIDA','PERIODO_ID','CIPREP_ID','LGOB_COD',
                        'DEPEN_ID1','DEPEN_ID2','DEPEN_ID3','EPPROG_ID','EPPROY_ID',
                        'FECHA_ENTREGA','FECHA_ENTREGA2','FECHA_ENTREGA3','PERIODO_ID1','MES_ID1','DIA_ID1','MES_ID2',
                        'PROGRAMA_ID','PROGRAMA_DESC','ACTIVIDAD_ID','ACTIVIDAD_DESC','OBJETIVO_ID','OBJETIVO_DESC',
                        'OPERACIONAL_DESC','UMED_ID','MESP_01','MESP_02','MESP_03','MESP_04','MESP_05','MESP_06',
                        'MESP_07','MESP_08','MESP_09','MESP_10','MESP_11','MESP_12',
                        'MESC_01','MESC_02','MESC_03','MESC_04','MESC_05','MESC_06',
                        'MESC_07','MESC_08','MESC_09','MESC_10','MESC_11','MESC_12',
                        'TRIMP_01','TRIMP_02','TRIMP_03','TRIMP_04','TOTP_01','TOTP_02',
                        'TRIMC_01','TRIMC_02','TRIMC_03','TRIMC_04','TOTC_01','TOTC_02',
                        'TSEMP_01','TSEMP_02','TSEMC_01','TSEMC_02',   
                        'MES_P01','MES_P02','MES_P03','MES_P04','MES_P05','MES_P06',
                        'MES_P07','MES_P08','MES_P09','MES_P10','MES_P11','MES_P12',
                        'TRIM_P01','TRIM_P02','TRIM_P03','TRIM_P04','SEM_P01','SEM_P02','TOT_P01',
                        'SEMAF_01','SEMAF_02','SEMAF_03','SEMAF_04','SEMAF_05','SEMAF_06',
                        'SEMAF_07','SEMAF_08','SEMAF_09','SEMAF_10','SEMAF_11','SEMAF_12',
                        'SEMAFT_01','SEMAFT_02','SEMAFT_03','SEMAFT_04','SEMAFS_01','SEMAFS_02','SEMAFA_01',
                        'SOPORTE_ID','SOPORTE_01','SOPORTE_02','SOPORTE_03','SOPORTE_04','SOPORTE_05','SOPORTE_06',
                        'SOPORTE_07','SOPORTE_08','SOPORTE_09','SOPORTE_10','SOPORTE_11','SOPORTE_12',
                        'OBS_01','OBS_02',
                        'STATUS_1','STATUS_2','FECREG','FECREG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')               
                        ->where(['FOLIO' => $id, 'PARTIDA' => $id2])
                        //->where('FOLIO',$id)
                        //->where('PARTIDA',$id2)
                        ->first();  
        if($regprogdanual->count() <= 0){
            toastr()->error('No existen registros de acciones o metas del Programa anual.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }
        return view('sicinar.informelabores.editarSoportedet10',compact('nombre','usuario','regdepen','reganios','regperiodos','regmeses','regdias','regprogeanual','regprogdanual','regumedida','regepproy','regepprog'));
    }

    public function actionActualizarSoportedet10(soportedet10Request $request, $id, $id2){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango');
        $ip            = session()->get('ip');
        $arbol_id      = session()->get('arbol_id');    
        $depen_id      = session()->get('depen_id');                    

        // **************** actualizar ******************************
        $regprogdanual= regProgdAnualModel::where(['FOLIO' => $id, 'PARTIDA' => $id2]);
        if($regprogdanual->count() <= 0)
            toastr()->error('No existe acción o meta del programa anual.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            //********************** Actualizar ********************************/
            $name10 =null;
            if($request->hasFile('soporte_10')){
                $name10 = $request->periodo_id.'_'.$id.'_'.$id2.'_'.$request->file('soporte_10')->getClientOriginalName(); 
                $request->file('soporte_10')->move(public_path().'/storage/', $name10);

                $regprogdanual = regProgdAnualModel::where(['FOLIO' => $id, 'PARTIDA' => $id2])        
                                 ->update([                
                                           'SOPORTE_10' => $name10,

                                           'IP_M'       => $ip,
                                           'LOGIN_M'    => $nombre,
                                           'FECHA_M2'   => date('Y/m/d'),    //date('d/m/Y')
                                           'FECHA_M'    => date('Y/m/d')    //date('d/m/Y')                                
                                           ]);
                toastr()->success('Archivo digital justificación actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);

                /************ Bitacora inicia *************************************/ 
                setlocale(LC_TIME, "spanish");        
                $xip          = session()->get('ip');
                $xperiodo_id  = (int)date('Y');
                $xprograma_id = 1;
                $xmes_id      = (int)date('m');
                $xproceso_id  =         3;
                $xfuncion_id  =      3013;
                $xtrx_id      =        38;    //Actualizar 
                $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID', 'FUNCION_ID', 
                               'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                               ->where(['PERIODO_ID' => $xperiodo_id,  'MES_ID' => $xmes_id, 
                                        'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 
                                        'FOLIO' => $id])
                               ->get();
                if($regbitacora->count() <= 0){              // Alta
                    $nuevoregBitacora = new regBitacoraModel();              
                    $nuevoregBitacora->PERIODO_ID = $xperiodo_id;    // Año de transaccion 
                    $nuevoregBitacora->PROGRAMA_ID= $xprograma_id;   // Proyecto JAPEM 
                    $nuevoregBitacora->MES_ID     = $xmes_id;        // Mes de transaccion
                    $nuevoregBitacora->PROCESO_ID = $xproceso_id;    // Proceso de apoyo
                    $nuevoregBitacora->FUNCION_ID = $xfuncion_id;    // Funcion del modelado de procesos 
                    $nuevoregBitacora->TRX_ID     = $xtrx_id;        // Actividad del modelado de procesos
                    $nuevoregBitacora->FOLIO      = $id;             // Folio    
                    $nuevoregBitacora->NO_VECES   = 1;               // Numero de veces            
                    $nuevoregBitacora->IP         = $ip;             // IP
                    $nuevoregBitacora->LOGIN      = $nombre;         // Usuario 
                    $nuevoregBitacora->save();
                    if($nuevoregBitacora->save() == true)
                        toastr()->success('Trx de Archivo digital de justificación registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                    else
                        toastr()->error('Error en Trx de Archivo digital de justificación. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
                }else{                   
                    //*********** Obtine el no. de veces *****************************
                    $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id,'MES_ID'    => $xmes_id,
                                                          'PROCESO_ID' => $xproceso_id,'FUNCION_ID'=> $xfuncion_id, 
                                                          'TRX_ID'     => $xtrx_id    ,'FOLIO'     => $id])
                                 ->max('NO_VECES');
                    $xno_veces = $xno_veces+1;                        
                    //*********** Termina de obtener el no de veces *****************************         
                    $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                                   ->where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                            'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id, 'FOLIO'      => $id])
                                   ->update([
                                             'NO_VECES'=> $regbitacora->NO_VECES = $xno_veces,
                                             'IP_M'    => $regbitacora->IP       = $ip,
                                             'LOGIN_M' => $regbitacora->LOGIN_M  = $nombre,
                                             'FECHA_M' => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                             ]);
                    toastr()->success('Trx de Archivo digital de justificación actualizado en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                }   /************ Bitacora termina *************************************/       
            }       /************ Valida si viene vacio el arc. digital ****************/                              
        }           /************ Actualizar *******************************************/
        return redirect()->route('versoportesdet',$id);
    }   

    public function actionEditarSoportedet11($id,$id2){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $arbol_id     = session()->get('arbol_id');        
        $depen_id     = session()->get('depen_id');                

        $regumedida   = regUmedidaModel::select('UMED_ID','UMED_DESC')
                        ->orderBy('UMED_ID','asc')
                        ->get();      
        $regperiodos  = regPeriodosModel::select('PERIODO_ID', 'PERIODO_DESC')
                        ->get();         
        $reganios     = regAniosModel::select('ANIO_ID','ANIO_DESC')
                        ->get();        
        $regmeses     = regMesesModel::select('MES_ID','MES_DESC')
                        ->get();      
        $regdias      = regDiasModel::select('DIA_ID','DIA_DESC')
                        ->get();                                
        $regepprog    = regEPprogramaModel::select('EPPROG_ID','EPPROG_DESC')
                        ->orderBy('EPPROG_ID','asc')
                        ->get();                         
        $regepproy    = regEPproyectoModel::select('EPPROY_ID','EPPROY_DESC')
                        ->orderBy('EPPROY_ID','asc')
                        ->get();                
        //********* Validar rol de usuario **********************/
        if(session()->get('rango') !== '0'){                          
            $regdepen = regdepenModel::select('DEPEN_ID', 'DEPEN_DESC')
                        ->where('DEPEN_STATUS','1')
                        ->get();                                                         
        }else{
            $regdepen = regdepenModel::select('DEPEN_ID', 'DEPEN_DESC')
                        ->where('DEPEN_STATUS','1')
                        ->where('DEPEN_ID',$depen_id)
                        ->get();               
        }                    
        $regprogeanual= regProgeAnualModel::select('FOLIO','PERIODO_ID','DEPEN_ID1','DEPEN_ID2','DEPEN_ID3',
                        'EPPROG_ID','EPPROY_ID','FECHA_ENTREGA','FECHA_ENTREGA2','FECHA_ENTREGA3',
                        'PERIODO_ID1','MES_ID1','DIA_ID1','MES_ID2','PROGRAMA_ID','PROGRAMA_DESC',
                        'RESPONSABLE','ELABORO','AUTORIZO','OBS_1','OBS_2','STATUS_1','STATUS_2',
                        'FECREG','FECREG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')
                        ->where(  'FOLIO'     ,$id)
                        ->orderBy('PERIODO_ID','ASC')
                        ->orderBy('FOLIO'     ,'ASC')
                        ->get();
        $regprogdanual= regProgdAnualModel::select('FOLIO','PARTIDA','PERIODO_ID','CIPREP_ID','LGOB_COD',
                        'DEPEN_ID1','DEPEN_ID2','DEPEN_ID3','EPPROG_ID','EPPROY_ID',
                        'FECHA_ENTREGA','FECHA_ENTREGA2','FECHA_ENTREGA3','PERIODO_ID1','MES_ID1','DIA_ID1','MES_ID2',
                        'PROGRAMA_ID','PROGRAMA_DESC','ACTIVIDAD_ID','ACTIVIDAD_DESC','OBJETIVO_ID','OBJETIVO_DESC',
                        'OPERACIONAL_DESC','UMED_ID','MESP_01','MESP_02','MESP_03','MESP_04','MESP_05','MESP_06',
                        'MESP_07','MESP_08','MESP_09','MESP_10','MESP_11','MESP_12',
                        'MESC_01','MESC_02','MESC_03','MESC_04','MESC_05','MESC_06',
                        'MESC_07','MESC_08','MESC_09','MESC_10','MESC_11','MESC_12',
                        'TRIMP_01','TRIMP_02','TRIMP_03','TRIMP_04','TOTP_01','TOTP_02',
                        'TRIMC_01','TRIMC_02','TRIMC_03','TRIMC_04','TOTC_01','TOTC_02',
                        'TSEMP_01','TSEMP_02','TSEMC_01','TSEMC_02',   
                        'MES_P01','MES_P02','MES_P03','MES_P04','MES_P05','MES_P06',
                        'MES_P07','MES_P08','MES_P09','MES_P10','MES_P11','MES_P12',
                        'TRIM_P01','TRIM_P02','TRIM_P03','TRIM_P04','SEM_P01','SEM_P02','TOT_P01',
                        'SEMAF_01','SEMAF_02','SEMAF_03','SEMAF_04','SEMAF_05','SEMAF_06',
                        'SEMAF_07','SEMAF_08','SEMAF_09','SEMAF_10','SEMAF_11','SEMAF_12',
                        'SEMAFT_01','SEMAFT_02','SEMAFT_03','SEMAFT_04','SEMAFS_01','SEMAFS_02','SEMAFA_01',
                        'SOPORTE_ID','SOPORTE_01','SOPORTE_02','SOPORTE_03','SOPORTE_04','SOPORTE_05','SOPORTE_06',
                        'SOPORTE_07','SOPORTE_08','SOPORTE_09','SOPORTE_10','SOPORTE_11','SOPORTE_12',
                        'OBS_01','OBS_02',
                        'STATUS_1','STATUS_2','FECREG','FECREG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')               
                        ->where(['FOLIO' => $id, 'PARTIDA' => $id2])
                        //->where('FOLIO',$id)
                        //->where('PARTIDA',$id2)
                        ->first();  
        if($regprogdanual->count() <= 0){
            toastr()->error('No existen registros de acciones o metas del Programa anual.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }
        return view('sicinar.informelabores.editarSoportedet11',compact('nombre','usuario','regdepen','reganios','regperiodos','regmeses','regdias','regprogeanual','regprogdanual','regumedida','regepproy','regepprog'));
    }

    public function actionActualizarSoportedet11(soportedet11Request $request, $id, $id2){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango');
        $ip            = session()->get('ip');
        $arbol_id      = session()->get('arbol_id');    
        $depen_id      = session()->get('depen_id');                    

        // **************** actualizar ******************************
        $regprogdanual= regProgdAnualModel::where(['FOLIO' => $id, 'PARTIDA' => $id2]);
        if($regprogdanual->count() <= 0)
            toastr()->error('No existe acción o meta del programa anual.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            //********************** Actualizar ********************************/
            $name11 =null;
            if($request->hasFile('soporte_11')){
                $name11 = $request->periodo_id.'_'.$id.'_'.$id2.'_'.$request->file('soporte_11')->getClientOriginalName(); 
                $request->file('soporte_11')->move(public_path().'/storage/', $name11);

                $regprogdanual = regProgdAnualModel::where(['FOLIO' => $id, 'PARTIDA' => $id2])        
                                 ->update([                
                                           'SOPORTE_11' => $name11,

                                           'IP_M'       => $ip,
                                           'LOGIN_M'    => $nombre,
                                           'FECHA_M2'   => date('Y/m/d'),    //date('d/m/Y')
                                           'FECHA_M'    => date('Y/m/d')    //date('d/m/Y')                                
                                           ]);
                toastr()->success('Archivo digital justificación actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);

                /************ Bitacora inicia *************************************/ 
                setlocale(LC_TIME, "spanish");        
                $xip          = session()->get('ip');
                $xperiodo_id  = (int)date('Y');
                $xprograma_id = 1;
                $xmes_id      = (int)date('m');
                $xproceso_id  =         3;
                $xfuncion_id  =      3013;
                $xtrx_id      =        38;    //Actualizar 
                $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID', 'FUNCION_ID', 
                               'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                               ->where(['PERIODO_ID' => $xperiodo_id,  'MES_ID' => $xmes_id, 
                                        'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 
                                        'FOLIO' => $id])
                               ->get();
                if($regbitacora->count() <= 0){              // Alta
                    $nuevoregBitacora = new regBitacoraModel();              
                    $nuevoregBitacora->PERIODO_ID = $xperiodo_id;    // Año de transaccion 
                    $nuevoregBitacora->PROGRAMA_ID= $xprograma_id;   // Proyecto JAPEM 
                    $nuevoregBitacora->MES_ID     = $xmes_id;        // Mes de transaccion
                    $nuevoregBitacora->PROCESO_ID = $xproceso_id;    // Proceso de apoyo
                    $nuevoregBitacora->FUNCION_ID = $xfuncion_id;    // Funcion del modelado de procesos 
                    $nuevoregBitacora->TRX_ID     = $xtrx_id;        // Actividad del modelado de procesos
                    $nuevoregBitacora->FOLIO      = $id;             // Folio    
                    $nuevoregBitacora->NO_VECES   = 1;               // Numero de veces            
                    $nuevoregBitacora->IP         = $ip;             // IP
                    $nuevoregBitacora->LOGIN      = $nombre;         // Usuario 
                    $nuevoregBitacora->save();
                    if($nuevoregBitacora->save() == true)
                        toastr()->success('Trx de Archivo digital de justificación registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                    else
                        toastr()->error('Error en Trx de Archivo digital de justificación. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
                }else{                   
                    //*********** Obtine el no. de veces *****************************
                    $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id,'MES_ID'    => $xmes_id,
                                                          'PROCESO_ID' => $xproceso_id,'FUNCION_ID'=> $xfuncion_id, 
                                                          'TRX_ID'     => $xtrx_id    ,'FOLIO'     => $id])
                                 ->max('NO_VECES');
                    $xno_veces = $xno_veces+1;                        
                    //*********** Termina de obtener el no de veces *****************************         
                    $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                                   ->where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                            'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id, 'FOLIO'      => $id])
                                   ->update([
                                             'NO_VECES'=> $regbitacora->NO_VECES = $xno_veces,
                                             'IP_M'    => $regbitacora->IP       = $ip,
                                             'LOGIN_M' => $regbitacora->LOGIN_M  = $nombre,
                                             'FECHA_M' => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                             ]);
                    toastr()->success('Trx de Archivo digital de justificación actualizado en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                }   /************ Bitacora termina *************************************/       
            }       /************ Valida si viene vacio el arc. digital ****************/                              
        }           /************ Actualizar *******************************************/
        return redirect()->route('versoportesdet',$id);
    }   

    public function actionEditarSoportedet12($id,$id2){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $arbol_id     = session()->get('arbol_id');        
        $depen_id     = session()->get('depen_id');                

        $regumedida   = regUmedidaModel::select('UMED_ID','UMED_DESC')
                        ->orderBy('UMED_ID','asc')
                        ->get();      
        $regperiodos  = regPeriodosModel::select('PERIODO_ID', 'PERIODO_DESC')
                        ->get();         
        $reganios     = regAniosModel::select('ANIO_ID','ANIO_DESC')
                        ->get();        
        $regmeses     = regMesesModel::select('MES_ID','MES_DESC')
                        ->get();      
        $regdias      = regDiasModel::select('DIA_ID','DIA_DESC')
                        ->get();                                
        $regepprog    = regEPprogramaModel::select('EPPROG_ID','EPPROG_DESC')
                        ->orderBy('EPPROG_ID','asc')
                        ->get();                         
        $regepproy    = regEPproyectoModel::select('EPPROY_ID','EPPROY_DESC')
                        ->orderBy('EPPROY_ID','asc')
                        ->get();                
        //********* Validar rol de usuario **********************/
        if(session()->get('rango') !== '0'){                          
            $regdepen = regdepenModel::select('DEPEN_ID', 'DEPEN_DESC')
                        ->where('DEPEN_STATUS','1')
                        ->get();                                                         
        }else{
            $regdepen = regdepenModel::select('DEPEN_ID', 'DEPEN_DESC')
                        ->where('DEPEN_STATUS','1')
                        ->where('DEPEN_ID',$depen_id)
                        ->get();               
        }                    
        $regprogeanual= regProgeAnualModel::select('FOLIO','PERIODO_ID','DEPEN_ID1','DEPEN_ID2','DEPEN_ID3',
                        'EPPROG_ID','EPPROY_ID','FECHA_ENTREGA','FECHA_ENTREGA2','FECHA_ENTREGA3',
                        'PERIODO_ID1','MES_ID1','DIA_ID1','MES_ID2','PROGRAMA_ID','PROGRAMA_DESC',
                        'RESPONSABLE','ELABORO','AUTORIZO','OBS_1','OBS_2','STATUS_1','STATUS_2',
                        'FECREG','FECREG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')
                        ->where(  'FOLIO'     ,$id)
                        ->orderBy('PERIODO_ID','ASC')
                        ->orderBy('FOLIO'     ,'ASC')
                        ->get();
        $regprogdanual= regProgdAnualModel::select('FOLIO','PARTIDA','PERIODO_ID','CIPREP_ID','LGOB_COD',
                        'DEPEN_ID1','DEPEN_ID2','DEPEN_ID3','EPPROG_ID','EPPROY_ID',
                        'FECHA_ENTREGA','FECHA_ENTREGA2','FECHA_ENTREGA3','PERIODO_ID1','MES_ID1','DIA_ID1','MES_ID2',
                        'PROGRAMA_ID','PROGRAMA_DESC','ACTIVIDAD_ID','ACTIVIDAD_DESC','OBJETIVO_ID','OBJETIVO_DESC',
                        'OPERACIONAL_DESC','UMED_ID','MESP_01','MESP_02','MESP_03','MESP_04','MESP_05','MESP_06',
                        'MESP_07','MESP_08','MESP_09','MESP_10','MESP_11','MESP_12',
                        'MESC_01','MESC_02','MESC_03','MESC_04','MESC_05','MESC_06',
                        'MESC_07','MESC_08','MESC_09','MESC_10','MESC_11','MESC_12',
                        'TRIMP_01','TRIMP_02','TRIMP_03','TRIMP_04','TOTP_01','TOTP_02',
                        'TRIMC_01','TRIMC_02','TRIMC_03','TRIMC_04','TOTC_01','TOTC_02',
                        'TSEMP_01','TSEMP_02','TSEMC_01','TSEMC_02',   
                        'MES_P01','MES_P02','MES_P03','MES_P04','MES_P05','MES_P06',
                        'MES_P07','MES_P08','MES_P09','MES_P10','MES_P11','MES_P12',
                        'TRIM_P01','TRIM_P02','TRIM_P03','TRIM_P04','SEM_P01','SEM_P02','TOT_P01',
                        'SEMAF_01','SEMAF_02','SEMAF_03','SEMAF_04','SEMAF_05','SEMAF_06',
                        'SEMAF_07','SEMAF_08','SEMAF_09','SEMAF_10','SEMAF_11','SEMAF_12',
                        'SEMAFT_01','SEMAFT_02','SEMAFT_03','SEMAFT_04','SEMAFS_01','SEMAFS_02','SEMAFA_01',
                        'SOPORTE_ID','SOPORTE_01','SOPORTE_02','SOPORTE_03','SOPORTE_04','SOPORTE_05','SOPORTE_06',
                        'SOPORTE_07','SOPORTE_08','SOPORTE_09','SOPORTE_10','SOPORTE_11','SOPORTE_12',
                        'OBS_01','OBS_02',
                        'STATUS_1','STATUS_2','FECREG','FECREG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')               
                        ->where(['FOLIO' => $id, 'PARTIDA' => $id2])
                        //->where('FOLIO',$id)
                        //->where('PARTIDA',$id2)
                        ->first();  
        if($regprogdanual->count() <= 0){
            toastr()->error('No existen registros de acciones o metas del Programa anual.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }
        return view('sicinar.informelabores.editarSoportedet12',compact('nombre','usuario','regdepen','reganios','regperiodos','regmeses','regdias','regprogeanual','regprogdanual','regumedida','regepproy','regepprog'));
    }

    public function actionActualizarSoportedet12(soportedet12Request $request, $id, $id2){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango');
        $ip            = session()->get('ip');
        $arbol_id      = session()->get('arbol_id');    
        $depen_id      = session()->get('depen_id');                    

        // **************** actualizar ******************************
        $regprogdanual= regProgdAnualModel::where(['FOLIO' => $id, 'PARTIDA' => $id2]);
        if($regprogdanual->count() <= 0)
            toastr()->error('No existe acción o meta del programa anual.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            //********************** Actualizar ********************************/
            $name12 =null;
            if($request->hasFile('soporte_12')){
                $name12 = $request->periodo_id.'_'.$id.'_'.$id2.'_'.$request->file('soporte_12')->getClientOriginalName(); 
                $request->file('soporte_12')->move(public_path().'/storage/', $name12);

                $regprogdanual = regProgdAnualModel::where(['FOLIO' => $id, 'PARTIDA' => $id2])        
                                 ->update([                
                                           'SOPORTE_12' => $name12,

                                           'IP_M'       => $ip,
                                           'LOGIN_M'    => $nombre,
                                           'FECHA_M2'   => date('Y/m/d'),    //date('d/m/Y')
                                           'FECHA_M'    => date('Y/m/d')    //date('d/m/Y')                                
                                           ]);
                toastr()->success('Archivo digital justificación actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);

                /************ Bitacora inicia *************************************/ 
                setlocale(LC_TIME, "spanish");        
                $xip          = session()->get('ip');
                $xperiodo_id  = (int)date('Y');
                $xprograma_id = 1;
                $xmes_id      = (int)date('m');
                $xproceso_id  =         3;
                $xfuncion_id  =      3013;
                $xtrx_id      =        38;    //Actualizar 
                $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID', 'FUNCION_ID', 
                               'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                               ->where(['PERIODO_ID' => $xperiodo_id,  'MES_ID' => $xmes_id, 
                                        'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 
                                        'FOLIO' => $id])
                               ->get();
                if($regbitacora->count() <= 0){              // Alta
                    $nuevoregBitacora = new regBitacoraModel();              
                    $nuevoregBitacora->PERIODO_ID = $xperiodo_id;    // Año de transaccion 
                    $nuevoregBitacora->PROGRAMA_ID= $xprograma_id;   // Proyecto JAPEM 
                    $nuevoregBitacora->MES_ID     = $xmes_id;        // Mes de transaccion
                    $nuevoregBitacora->PROCESO_ID = $xproceso_id;    // Proceso de apoyo
                    $nuevoregBitacora->FUNCION_ID = $xfuncion_id;    // Funcion del modelado de procesos 
                    $nuevoregBitacora->TRX_ID     = $xtrx_id;        // Actividad del modelado de procesos
                    $nuevoregBitacora->FOLIO      = $id;             // Folio    
                    $nuevoregBitacora->NO_VECES   = 1;               // Numero de veces            
                    $nuevoregBitacora->IP         = $ip;             // IP
                    $nuevoregBitacora->LOGIN      = $nombre;         // Usuario 
                    $nuevoregBitacora->save();
                    if($nuevoregBitacora->save() == true)
                        toastr()->success('Trx de Archivo digital de justificación registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                    else
                        toastr()->error('Error en Trx de Archivo digital de justificación. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
                }else{                   
                    //*********** Obtine el no. de veces *****************************
                    $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id,'MES_ID'    => $xmes_id,
                                                          'PROCESO_ID' => $xproceso_id,'FUNCION_ID'=> $xfuncion_id, 
                                                          'TRX_ID'     => $xtrx_id    ,'FOLIO'     => $id])
                                 ->max('NO_VECES');
                    $xno_veces = $xno_veces+1;                        
                    //*********** Termina de obtener el no de veces *****************************         
                    $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                                   ->where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                            'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id, 'FOLIO'      => $id])
                                   ->update([
                                             'NO_VECES'=> $regbitacora->NO_VECES = $xno_veces,
                                             'IP_M'    => $regbitacora->IP       = $ip,
                                             'LOGIN_M' => $regbitacora->LOGIN_M  = $nombre,
                                             'FECHA_M' => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                             ]);
                    toastr()->success('Trx de Archivo digital de justificación actualizado en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                }   /************ Bitacora termina *************************************/       
            }       /************ Valida si viene vacio el arc. digital ****************/                              
        }           /************ Actualizar *******************************************/
        return redirect()->route('versoportesdet',$id);
    }   

}
