<?php
//**************************************************************/
//* File:       proganualController.php
//* Función:    Programa Anual encabezado (PA)
//* Autor:      Ing. Silverio Baltazar Barrientos Zarate
//* Modifico:   Ing. Silverio Baltazar Barrientos Zarate
//* Fecha act.: mayo 2022
//* @Derechos reservados. Gobierno del Estado de México
//*************************************************************/
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\progeanualRequest;
use App\Http\Requests\progdanualRequest;
use App\Http\Requests\progdanual1Request;

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
use App\Exports\ExportProganualExcel;
use Maatwebsite\Excel\Facades\Excel;
// Exportar a pdf
use PDF;
//use Options;

class proganualController extends Controller
{

    public function actionBuscarPA(Request $request)
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
        $regepprog    = regEPprogramaModel::select('EPPROG_ID','EPPROG_DESC')
                        ->orderBy('EPPROG_ID','asc')
                        ->get();                         
        $regepproy    = regEPproyectoModel::select('EPPROy_ID','EPPROy_DESC')
                        ->orderBy('EPPROy_ID','asc')
                        ->get();   
        $regtipometa  = regTipometaModel::select('TACCION_ID','TACCION_DESC')
                        ->orderBy('TACCION_ID','asc')
                        ->get();                                   
        $totactivs    = regProgeAnualModel::join('SIEVAD_DPA','SIEVAD_DPA.FOLIO', '=','SIEVAD_EPA.FOLIO')
                        ->select(   'SIEVAD_EPA.PERIODO_ID','SIEVAD_EPA.FOLIO')
                        ->selectRaw('COUNT(*) AS TOTAL')
                        ->groupBy(  'SIEVAD_EPA.PERIODO_ID','SIEVAD_EPA.FOLIO')
                        ->get();                              
        //**************************************************************//
        // ***** busqueda https://github.com/rimorsoft/Search-simple ***//
        // ***** video https://www.youtube.com/watch?v=bmtD9GUaszw   ***//                            
        //**************************************************************//
        $folio    = $request->get('folio');   
        $idd      = $request->get('idd');   
        //$name   = $request->get('name');           
        //$acti   = $request->get('acti');  
        //$bio    = $request->get('bio');   
        //$nameiap= $request->get('nameiap');  
        if(session()->get('rango') !== '0'){    
            $regdepen     = regdepenModel::select('DEPEN_ID', 'DEPEN_DESC')
                            ->where('DEPEN_STATUS','1')
                            ->get();                                 
            $regprogeanual= regProgeAnualModel::join('SIEVAD_CAT_DEPENDENCIAS','SIEVAD_CAT_DEPENDENCIAS.DEPEN_ID','=',
                                                                               'SIEVAD_EPA.DEPEN_ID1')
                            ->select( 'SIEVAD_CAT_DEPENDENCIAS.DEPEN_DESC','SIEVAD_EPA.*')
                            ->orderBy('SIEVAD_EPA.PERIODO_ID','ASC')
                            ->orderBy('SIEVAD_EPA.DEPEN_ID1' ,'ASC')
                            ->orderBy('SIEVAD_EPA.FOLIO'     ,'ASC')
                            //->name($name)     //Metodos equvalente a ->where('DEPEN_DESC', 'LIKE', "%$name%");
                            //->acti($acti)     //Metodos personalizados
                            ->idd($idd)         //Metodos personalizados
                            ->folio($folio)     //Metodos personalizados     
                            //->nameiap($nameiap) //Metodos personalizados  
                            ->paginate(50); 
        }else{
            $regdepen     = regdepenModel::select('DEPEN_ID', 'DEPEN_DESC')
                            ->where('DEPEN_ID',$depen_id)
                            ->get();                                        
            $regprogeanual= regProgeAnualModel::join('SIEVAD_CAT_DEPENDENCIAS','SIEVAD_CAT_DEPENDENCIAS.DEPEN_ID','=',
                                                                               'SIEVAD_EPA.DEPEN_ID1')
                            ->select( 'SIEVAD_CAT_DEPENDENCIAS.DEPEN_DESC','SIEVAD_EPA.*')
                            ->where(  'SIEVAD_EPA.DEPEN_ID1' ,$depen_id)
                            ->orderBy('SIEVAD_EPA.PERIODO_ID','ASC')                          
                            ->orderBy('SIEVAD_EPA.DEPEN_ID1' ,'ASC')
                            ->orderBy('SIEVAD_EPA.FOLIO'     ,'ASC')                          
                            ->idd($idd)         //Metodos personalizados                      
                            ->folio($folio)      //Metodos personalizados es equvalente a ->where('DEPEN_DESC', 'LIKE', "%$name%");
                            //->nameiap($nameiap) //Metodos personalizados                                  
                            //->email($email)   //Metodos personalizados
                            //->bio($bio)       //Metodos personalizados
                            ->paginate(50);   
        }                                                                          
        if($regprogeanual->count() <= 0){
            toastr()->error('No existen registros de Programa anual.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }            
        return view('sicinar.programa_anual.verProganual', compact('nombre','usuario','regdepen','reganios','regperiodos','regmeses','regdias','regprogeanual','totactivs','regepproy','regepprog','regtipometa'));
    }

    public function actionVerPA(){
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
        $regepprog    = regEPprogramaModel::select('EPPROG_ID','EPPROG_DESC')
                        ->orderBy('EPPROG_ID','asc')
                        ->get();                         
        $regepproy    = regEPproyectoModel::select('EPPROy_ID','EPPROy_DESC')
                        ->orderBy('EPPROy_ID','asc')
                        ->get();                          
        $regtipometa  = regTipometaModel::select('TACCION_ID','TACCION_DESC')
                        ->orderBy('TACCION_ID','asc')
                        ->get();                         
        //********* Validar rol de usuario **********************/
        if(session()->get('rango') !== '0'){  
            $regdepen     =regdepenModel::select('DEPEN_ID', 'DEPEN_DESC')
                           ->where(  'DEPEN_STATUS','1')
                           ->orderBy('DEPEN_ID'    ,'ASC')
                           ->get();   
            $totactivs    =regProgeAnualModel::join('SIEVAD_DPA','SIEVAD_DPA.FOLIO', '=','SIEVAD_EPA.FOLIO')
                           ->select(   'SIEVAD_EPA.PERIODO_ID','SIEVAD_EPA.FOLIO')
                           ->selectRaw('COUNT(*) AS TOTACTIVIDADES')
                           ->groupBy(  'SIEVAD_EPA.PERIODO_ID','SIEVAD_EPA.FOLIO')
                           ->get();                   
            $regprogeanual=regProgeAnualModel::select('FOLIO','PERIODO_ID','DEPEN_ID1','DEPEN_ID2','DEPEN_ID3',
                           'EPPROG_ID','EPPROY_ID','FECHA_ENTREGA','FECHA_ENTREGA2','FECHA_ENTREGA3',
                           'PERIODO_ID1','MES_ID1','DIA_ID1','MES_ID2','PROGRAMA_ID','PROGRAMA_DESC','TACCION_ID',
                           'RESPONSABLE','ELABORO','AUTORIZO','OBS_1','OBS_2','STATUS_1','STATUS_2',
                           'FECREG','FECREG2','IP','LOGIN')
                           ->orderBy('PERIODO_ID','ASC')
                           ->orderBy('DEPEN_ID1' ,'ASC')
                           ->orderBy('FOLIO'     ,'ASC')
                           ->paginate(50);
        }else{                  
            $regdepen     =regdepenModel::select('DEPEN_ID', 'DEPEN_DESC')
                           //->where('DEPEN_STATUS','1')
                           //->where('DEPEN_ID',$depen_id)
                           ->where([ ['DEPEN_STATUS','1'],['DEPEN_PADRE',Substr($depen_id,0,3)]  ])           
                           ->get();                            
            $totactivs    =regProgeAnualModel::join('SIEVAD_DPA','SIEVAD_DPA.FOLIO','=','SIEVAD_EPA.FOLIO')
                           ->select(   'SIEVAD_EPA.PERIODO_ID','SIEVAD_EPA.FOLIO')
                           ->selectRaw('COUNT(*) AS TOTACTIVIDADES')
                           ->where(    'SIEVAD_EPA.DEPEN_ID',$depen_id) 
                           ->groupBy(  'SIEVAD_EPA.PERIODO_ID','SIEVAD_EPA.FOLIO')
                           ->get();                               
            $regprogeanual=regProgeAnualModel::select('FOLIO','PERIODO_ID','DEPEN_ID1','DEPEN_ID2','DEPEN_ID3',
                           'EPPROG_ID','EPPROY_ID','FECHA_ENTREGA','FECHA_ENTREGA2','FECHA_ENTREGA3',
                           'PERIODO_ID1','MES_ID1','DIA_ID1','MES_ID2','PROGRAMA_ID','PROGRAMA_DESC','TACCION_ID',
                           'RESPONSABLE','ELABORO','AUTORIZO','OBS_1','OBS_2','STATUS_1','STATUS_2',
                           'FECREG','FECREG2','IP','LOGIN')
                           ->where(  'DEPEN_ID1' ,$depen_id)            
                           ->orderBy('PERIODO_ID','ASC')
                           ->orderBy('DEPEN_ID1' ,'ASC')
                           ->orderBy('FOLIO'     ,'ASC')  
                           ->paginate(50);          
        }                        
        if($regprogeanual->count() <= 0){
            toastr()->error('No existe Programa anual.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }
        return view('sicinar.programa_anual.verProganual',compact('nombre','usuario','regdepen','regperiodos','regprogeanual','totactivs','regepprog','regepproy','regtipometa')); 
    }

    public function actionNuevoPA(){
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
        $reganios     = regAniosModel::select('ANIO_ID','ANIO_DESC')
                        ->get();                              
        $regmeses     = regMesesModel::select('MES_ID','MES_DESC')
                        ->get();      
        $regdias      = regDiasModel::select('DIA_ID','DIA_DESC')
                        ->get();     
        $regepprog    = regEPprogramaModel::select('EPPROG_ID','EPPROG_DESC')
                        ->orderBy('EPPROG_DESC','asc')
                        ->get();                         
        $regepproy    = regEPproyectoModel::select('EPPROY_ID','EPPROY_DESC')
                        ->orderBy('EPPROY_DESC','asc')
                        ->get();     
        $regtipometa  = regTipometaModel::select('TACCION_ID','TACCION_DESC')
                        ->orderBy('TACCION_ID','asc')
                        ->get();                                                                                                  
        if(session()->get('rango') !== '0'){                           
            $regdepen = regdepenModel::select('DEPEN_ID', 'DEPEN_DESC')
                        ->where('DEPEN_STATUS','1')
                        ->orderBy('DEPEN_DESC','ASC')
                        ->get();                                                        
        }else{
            $regdepen = regdepenModel::select('DEPEN_ID', 'DEPEN_DESC')
                        ->where('DEPEN_STATUS','1')
                        ->where(  'DEPEN_ID',$depen_id)
                        ->orderBy('DEPEN_ID','ASC')
                        ->get();            
        }     
        $regprogeanual= regProgeAnualModel::select('FOLIO','PERIODO_ID','DEPEN_ID1','DEPEN_ID2','DEPEN_ID3',
                        'EPPROG_ID','EPPROY_ID','FECHA_ENTREGA','FECHA_ENTREGA2','FECHA_ENTREGA3',
                        'PERIODO_ID1','MES_ID1','DIA_ID1','MES_ID2','PROGRAMA_ID','PROGRAMA_DESC','TACCION_ID',
                        'RESPONSABLE','ELABORO','AUTORIZO','OBS_1','OBS_2','STATUS_1','STATUS_2',
                        'FECREG','FECREG2','IP','LOGIN')
                        ->orderBy('DEPEN_ID1','asc')
                        ->get();
        return view('sicinar.programa_anual.nuevoProganual',compact('regdepen','nombre','usuario','reganios','regperiodos','regmeses','regdias','regprogeanual','regepproy','regepprog','regtipometa'));
    }

    public function actionAltaNuevoPA(Request $request){
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
        $depen_id     = session()->get('depen_id');

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
        ////$duplicado = regProgeAnualModel::where(['PERIODO_ID' => $request->periodo_id,'DEPEN_ID1' => $request->depen_id1])
        ////             ->get();
        ////if($duplicado->count() >= 1)
        ////    return back()->withInput()->withErrors(['DEPEN_ID1' => 'UNIDAD_RESPONSABLE '.$request->DEPEN_ID1.' Ya existe Programa anual para la unidad responsable en el mismo periodo. Por favor verificar.']);
        ////else{  
            /************ ALTA  *****************************/ 
            //if(!empty($request->mes_d1) and !empty($request->dia_d1) ){
                ////toastr()->error('muy bien 1.....','¡ok!',['positionClass' => 'toast-bottom-right']);
                //$mes1 = regMesesModel::ObtMes($request->mes_id1);
                //$dia1 = regDiasModel::ObtDia($request->dia_id1);                
            //}   //endif

            $mes1 = regMesesModel::ObtMes($request->mes_id1);
            $dia1 = regDiasModel::ObtDia($request->dia_id1);                

            $folio = regProgeAnualModel::max('FOLIO');
            $folio = $folio + 1;
 
            $nuevoprogtrab = new regProgeAnualModel();
            $nuevoprogtrab->PERIODO_ID    = $request->periodo_id;             
            $nuevoprogtrab->FOLIO         = $folio;
            $nuevoprogtrab->DEPEN_ID1     = $request->depen_id1;
            $nuevoprogtrab->DEPEN_ID2     = $request->depen_id2;
            $nuevoprogtrab->EPPROG_ID     = $request->epprog_id;
            $nuevoprogtrab->EPPROY_ID     = $request->epproy_id;            
            $nuevoprogtrab->FECHA_ENTREGA = date('Y/m/d', strtotime(trim($request->periodo_id1.'/'.$mes1[0]->mes_mes.'/'.$dia1[0]->dia_desc) ));
            $nuevoprogtrab->FECHA_ENTREGA2= trim($dia1[0]->dia_desc.'/'.$mes1[0]->mes_mes.'/'.$request->periodo_id1);
            $nuevoprogtrab->FECHA_ENTREGA3= date('Y/m/d', strtotime(trim($request->periodo_id1.'/'.$mes1[0]->mes_mes.'/'.$dia1[0]->dia_desc) ));            
            $nuevoprogtrab->PERIODO_ID1   = $request->periodo_id1;                
            $nuevoprogtrab->MES_ID1       = $request->mes_id1;                
            $nuevoprogtrab->DIA_ID1       = $request->dia_id1;       
            $nuevoprogtrab->MES_ID2       = $request->mes_id2;
            $nuevoprogtrab->TACCION_ID    = $request->taccion_id;

            $nuevoprogtrab->RESPONSABLE   = substr(trim(strtoupper($request->responsable)),0,  99);
            $nuevoprogtrab->ELABORO       = substr(trim(strtoupper($request->elaboro))    ,0,  99);
            $nuevoprogtrab->AUTORIZO      = substr(trim(strtoupper($request->autorizo))   ,0,  99);
            $nuevoprogtrab->OBS_1         = substr(trim(strtoupper($request->obs_1))      ,0,1499);
        
            $nuevoprogtrab->IP            = $ip;
            $nuevoprogtrab->LOGIN         = $nombre;         // Usuario ;
            $nuevoprogtrab->save();
            if($nuevoprogtrab->save() == true){
                toastr()->success('Programa anual dado de alta.','ok!',['positionClass' => 'toast-bottom-right']);
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
                               ->where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id,
                                        'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id,'FOLIO'      => $folio])
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
                        toastr()->success('Trx de Programa anual dada de alta en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                    else
                        toastr()->error('Error trx. de Programa anual. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
                }else{                   
                    //*********** Obtine el no. de veces *****************************
                    $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id,'MES_ID'     => $xmes_id, 
                                                          'PROCESO_ID' => $xproceso_id,'FUNCION_ID' => $xfuncion_id, 
                                                          'TRX_ID'     => $xtrx_id,    'FOLIO'      => $folio])
                                 ->max('NO_VECES');
                    $xno_veces = $xno_veces+1;                        
                    //*********** Termina de obtener el no de veces *****************************         
                    $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                                   ->where(['PERIODO_ID' => $xperiodo_id,'MES_ID'     => $xmes_id, 
                                            'PROCESO_ID' => $xproceso_id,'FUNCION_ID' => $xfuncion_id,
                                            'TRX_ID'     => $xtrx_id,    'FOLIO'      => $folio])
                                   ->update([
                                             'NO_VECES'=> $regbitacora->NO_VECES = $xno_veces,
                                             'IP_M'    => $regbitacora->IP       = $ip,
                                             'LOGIN_M' => $regbitacora->LOGIN_M  = $nombre,
                                             'FECHA_M' => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                            ]);
                    toastr()->success('Trx de Programa anual registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                }   /************ Bitacora termina *************************************/ 
            }else{
                toastr()->error('Error en Trx Programa anual Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
            }   //**************** Termina la alta *******************/
        ////}       // ******************* Termina el duplicado **********/
        return redirect()->route('verpa');
    } 

    public function actionEditarPA($id){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango');
        $arbol_id      = session()->get('arbol_id');     
        $depen_id     = session()->get('depen_id');   

        $regumedida   = regUmedidaModel::select('UMED_ID','UMED_DESC')
                        ->orderBy('UMED_DESC','asc')
                        ->get();   
        $reganios     = regAniosModel::select('ANIO_ID','ANIO_DESC')
                        ->get();
        $regperiodos  = regPeriodosModel::select('PERIODO_ID', 'PERIODO_DESC')
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
        $regtipometa  = regTipometaModel::select('TACCION_ID','TACCION_DESC')
                        ->orderBy('TACCION_ID','asc')
                        ->get();                                                 
        //********* Validar rol de usuario **********************/
        if(session()->get('rango') !== '0'){                          
            $regdepen   = regdepenModel::select('DEPEN_ID', 'DEPEN_DESC')
                          ->where(  'DEPEN_STATUS','1')
                          ->orderBy('DEPEN_DESC','asc')
                          ->get();                                                        
        }else{
            $regdepen   = regdepenModel::select('DEPEN_ID', 'DEPEN_DESC')
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
                        ->orderBy('DEPEN_ID1'  ,'ASC')
                        ->first();
        if($regprogeanual->count() <= 0){
            toastr()->error('No existen registros de programa anual.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }
        return view('sicinar.programa_anual.editarProganual',compact('nombre','usuario','regdepen','reganios','regperiodos','regmeses','regdias','regprogeanual','regepproy','regepprog','regtipometa'));
    }

    public function actionActualizarPA(progeanualRequest $request, $id){
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

        // **************** actualizar ******************************
        $regprogeanual = regProgeAnualModel::where('FOLIO',$id);
        if($regprogeanual->count() <= 0)
            toastr()->error('No existe Programa anual.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            //********************** Actualizar ********************************/
            $mes1 = regMesesModel::ObtMes($request->mes_id1);
            $dia1 = regDiasModel::ObtDia($request->dia_id1);  
            $regprogeanual = regProgeAnualModel::where('FOLIO',$id)        
                             ->update([                
                'DEPEN_ID1'     => $request->depen_id1,
                'DEPEN_ID2'     => $request->depen_id2,
                'EPPROG_ID'     => $request->epprog_id,
                'EPPROY_ID'     => $request->epproy_id,

                'FECHA_ENTREGA' => date('Y/m/d', strtotime($request->periodo_id1.'/'.$mes1[0]->mes_mes.'/'.trim($dia1[0]->dia_desc) )),
                'FECHA_ENTREGA2'=> trim($dia1[0]->dia_desc.'/'.$mes1[0]->mes_mes.'/'.$request->periodo_id1), 
                'FECHA_ENTREGA3'=> date('Y/m/d', strtotime($request->periodo_id1.'/'.$mes1[0]->mes_mes.'/'.trim($dia1[0]->dia_desc) )),
                'PERIODO_ID1'   => $request->periodo_id1,
                'MES_ID1'       => $request->mes_id1,
                'DIA_ID1'       => $request->dia_id1,
                'MES_ID2'       => $request->mes_id2,  
                'TACCION_ID'    => $request->taccion_id,                                

                'ELABORO'       => substr(trim(strtoupper($request->elaboro))    ,0,  99),
                'RESPONSABLE'   => substr(trim(strtoupper($request->responsable)),0,  99),
                'AUTORIZO'      => substr(trim(strtoupper($request->autorizo))   ,0,  99),
                'OBS_1'         => substr(trim(strtoupper($request->obs_1))      ,0,1499),        
                'STATUS_1'      => $request->status_1,

                'IP_M'          => $ip,
                'LOGIN_M'       => $nombre,
                'FECHA_M'       => date('Y/m/d')    //date('d/m/Y')                                
                                   ]);
            toastr()->success('Programa anual actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3011;
            $xtrx_id      =        13;    //Actualizar 
            $regbitacora = regBitacoraModel::select('PERIODO_ID',  'MES_ID', 'PROCESO_ID', 'FUNCION_ID', 
                           'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                           ->where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id, 
                                    'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id,'FOLIO'      => $id])
                           ->get();
            if($regbitacora->count() <= 0){              // Alta
                $nuevoregBitacora = new regBitacoraModel();              
                $nuevoregBitacora->PERIODO_ID = $xperiodo_id;    // Año de transaccion 
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
                    toastr()->success('Trx actualización de Programa anual registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                else
                    toastr()->error('Error Trx de actualización de Programa anual. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
            }else{                   
                //*********** Obtine el no. de veces *****************************
                $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id,'MES_ID'     => $xmes_id, 
                                                      'PROCESO_ID' => $xproceso_id,'FUNCION_ID' => $xfuncion_id, 
                                                      'TRX_ID'     => $xtrx_id,    'FOLIO'      => $id])
                             ->max('NO_VECES');
                $xno_veces = $xno_veces+1;                        
                //*********** Termina de obtener el no de veces *****************************         
                $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                               ->where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id, 
                                        'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id,'FOLIO'      => $id])
                               ->update([
                                         'NO_VECES'=> $regbitacora->NO_VECES = $xno_veces,
                                         'IP_M'    => $regbitacora->IP       = $ip,
                                         'LOGIN_M' => $regbitacora->LOGIN_M  = $nombre,
                                         'FECHA_M' => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                         ]);
                toastr()->success('Trx de actualización de Programa anual registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/                     
        }       /************ Actualizar *******************************************/
        return redirect()->route('verpa');
    }

    public function actionBorrarPA($id){
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
        $depen_id     = session()->get('depen_id');              

        //************ Elimina PA encabezado ********************************//
        $regprogeanual  = regProgeAnualModel::where('FOLIO',$id);
        if($regprogeanual->count() <= 0)
            toastr()->error('No existe Programa anual.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            $regprogeanual->delete();
            toastr()->success('Programa anual eliminado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
        }   //********* Termina de eliminar encabezado **********************//

        //********* Elimina detalles del PA ****************************//
        $regprogdanual= regProgdAnualModel::where('FOLIO',$id);  
        if($regprogdanual->count() <= 0)
            toastr()->error('No existen acciones y/o metas del Programa anual.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            $regprogdanual->delete();
            toastr()->success('Acciones y/o metas del programa anual eliminadas.','¡Ok!',['positionClass' => 'toast-bottom-right']);

            //echo 'Ya entre a borrar registro..........';
            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3011;
            $xtrx_id      =        14;     // Baja 
            $regbitacora = regBitacoraModel::select('PERIODO_ID','MES_ID', 'PROCESO_ID','FUNCION_ID', 
                           'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                           ->where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id, 
                                    'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id,'FOLIO'      => $id])
                           ->get();
            if($regbitacora->count() <= 0){              // Alta
                $nuevoregBitacora = new regBitacoraModel();              
                $nuevoregBitacora->PERIODO_ID = $xperiodo_id;    // Año de transaccion 
                
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
                    toastr()->success('Trx de elimiar de Programa anual registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                else
                    toastr()->error('Error de Trx de elimiar de Programa anual al dar de alta en bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
            }else{                   
                //*********** Obtine el no. de veces *****************************
                $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id, 
                                                      'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id,'FOLIO'      => $id])
                             ->max('NO_VECES');
                $xno_veces = $xno_veces+1;                        
                //*********** Termina de obtener el no de veces *****************************         
                $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                               ->where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                        'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id, 'FOLIO'      => $id])
                               ->update([
                                         'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                                         'IP_M'     => $regbitacora->IP       = $ip,
                                         'LOGIN_M'  => $regbitacora->LOGIN_M  = $nombre,
                                         'FECHA_M'  => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                        ]);
                toastr()->success('Trx de elimiar de Programa anual registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   //************ Bitacora termina *************************************//                
        }       //************* Termina de eliminar detalles ************************//
        return redirect()->route('verpa');
    }    

    // exportar a formato excel
    public function actionExportProganualExcel($id){
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
        
        /************ Bitacora inicia *************************************/ 
        setlocale(LC_TIME, "spanish");        
        $xip          = session()->get('ip');
        $xperiodo_id  = (int)date('Y');
        $xprograma_id = 1;
        $xmes_id      = (int)date('m');
        $xproceso_id  =         3;
        $xfuncion_id  =      3011;
        $xtrx_id      =        15;            // Exportar a formato Excel
        $id           =         0;
        $regbitacora  = regBitacoraModel::select('PERIODO_ID',  'MES_ID', 'PROCESO_ID', 'FUNCION_ID', 
                        'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                        ->where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id, 
                                 'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id,'FOLIO'      => $id])
                        ->get();
        if($regbitacora->count() <= 0){              // Alta
            $nuevoregBitacora = new regBitacoraModel();              
            $nuevoregBitacora->PERIODO_ID = $xperiodo_id;    // Año de transaccion 
            
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
               toastr()->success('Trx de exportar a excel Programa anual registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            else
               toastr()->error('Error Trx de exportar a excel Programa anual. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
        }else{                   
            //*********** Obtine el no. de veces *****************************
            $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id,'MES_ID'     => $xmes_id,
                                                  'PROCESO_ID' => $xproceso_id,'FUNCION_ID' => $xfuncion_id, 
                                                  'TRX_ID'     => $xtrx_id,    'FOLIO'      => $id])
                         ->max('NO_VECES');
            $xno_veces = $xno_veces+1;                        
            //*********** Termina de obtener el no de veces *****************************                
            $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                           ->where(['PERIODO_ID' => $xperiodo_id, 'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id, 
                                    'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id,'FOLIO'      => $id])
                           ->update([
                                     'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                                     'IP_M'     => $regbitacora->IP       = $ip,
                                     'LOGIN_M'  => $regbitacora->LOGIN_M  = $nombre,
                                     'FECHA_M'  => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                    ]);
            toastr()->success('Trx de exportar a excel Programa anual registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
        }   /********************** Bitacora termina *************************************/  
        return Excel::download(new ExportProganualExcel, 'Programa_Anual_'.date('d-m-Y').'.xlsx');
    }

    // exportar a formato PDF
    public function actionExportPAPDF($id,$id2){
        set_time_limit(0);
        ini_set("memory_limit",-1);
        ini_set('max_execution_time', 0);

        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango');
        $ip           = session()->get('ip');
        $arbol_id     = session()->get('arbol_id');   
        $depen_id     = session()->get('depen_id');             

        /************ Bitacora inicia *************************************/ 
        setlocale(LC_TIME, "spanish");        
        $xip          = session()->get('ip');
        $xperiodo_id  = (int)date('Y');
        $xprograma_id = 1;
        $xmes_id      = (int)date('m');
        $xproceso_id  =         3;
        $xfuncion_id  =      3011;
        $xtrx_id      =        16;       //Exportar a formato PDF
        $id           =         0;
        $regbitacora = regBitacoraModel::select('PERIODO_ID',  'MES_ID', 'PROCESO_ID', 'FUNCION_ID', 
                       'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                       ->where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id,
                                'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id,'FOLIO'      => $id])
                       ->get();
        if($regbitacora->count() <= 0){              // Alta
            $nuevoregBitacora = new regBitacoraModel();              
            $nuevoregBitacora->PERIODO_ID = $xperiodo_id;    // Año de transaccion 
            
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
               toastr()->success('Trx de exportar a PDF Programa anual registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            else
               toastr()->error('Error de Trx de exportar a excel Programa anual. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
        }else{                   
            //*********** Obtine el no. de veces *****************************
            $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id, 
                                                  'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id,'FOLIO'      => $id])
                         ->max('NO_VECES');
            $xno_veces = $xno_veces+1;                        
            //*********** Termina de obtener el no de veces *****************************         
            $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                           ->where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id, 
                                    'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id,'FOLIO'      => $id])
                           ->update([
                                     'NO_VECES'=> $regbitacora->NO_VECES = $xno_veces,
                                     'IP_M'    => $regbitacora->IP       = $ip,
                                     'LOGIN_M' => $regbitacora->LOGIN_M  = $nombre,
                                     'FECHA_M' => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                    ]);
            toastr()->success('Trx de exportar a excel Programa anual actualizada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
        }   /************ Bitacora termina *************************************/ 

        $regdepen     = regdepenModel::select('DEPEN_ID', 'DEPEN_DESC')
                        ->where('DEPEN_STATUS','1')
                        ->get();   
        //$regumedida = regUmedidaModel::select('UMED_ID','UMED_DESC')
        //                ->orderBy('UMED_DESC','asc')
        //                ->get();   
        $reganios     = regAniosModel::select('ANIO_ID','ANIO_DESC')
                        ->get();
        $regperiodos  = regPeriodosModel::select('PERIODO_ID', 'PERIODO_DESC')
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
        $regtipometa  = regTipometaModel::select('TACCION_ID','TACCION_DESC')
                        ->orderBy('TACCION_ID','asc')
                        ->get();                                                                          
        //********* Validar rol de usuario **********************/
        if(session()->get('rango') !== '0'){                 
            $regprogeanual= regProgeAnualModel::select('FOLIO','PERIODO_ID','DEPEN_ID1','DEPEN_ID2','DEPEN_ID3',
                           'EPPROG_ID','EPPROY_ID','FECHA_ENTREGA','FECHA_ENTREGA2','FECHA_ENTREGA3',
                           'PERIODO_ID1','MES_ID1','DIA_ID1','MES_ID2','PROGRAMA_ID','PROGRAMA_DESC','TACCION_ID',
                           'RESPONSABLE','ELABORO','AUTORIZO','OBS_1','OBS_2','STATUS_1','STATUS_2',
                           'FECREG','FECREG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')
                          ->where('FOLIO',$id2)                                 
                          ->get();
        }else{
            $regprogeanual= regProgeAnualModel::select('FOLIO','PERIODO_ID','DEPEN_ID1','DEPEN_ID2','DEPEN_ID3',
                           'EPPROG_ID','EPPROY_ID','FECHA_ENTREGA','FECHA_ENTREGA2','FECHA_ENTREGA3',
                           'PERIODO_ID1','MES_ID1','DIA_ID1','MES_ID2','PROGRAMA_ID','PROGRAMA_DESC','TACCION_ID',
                           'RESPONSABLE','ELABORO','AUTORIZO','OBS_1','OBS_2','STATUS_1','STATUS_2',
                           'FECREG','FECREG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')
                          ->where(['FOLIO' => $id2,'DEPEN_ID' => $depen_id])
                          ->orderBy('PERIODO_ID','ASC')
                          ->orderBy('FOLIO'     ,'ASC')
                          ->get();            
        }                                       
        $regprogdanual=regProgdAnualModel::join('SIEVAD_CAT_UMEDIDA' ,'SIEVAD_CAT_UMEDIDA.UMED_ID','=',
                                                                      'SIEVAD_DPA.UMED_ID')
                                         ->join('SIEVAD_CAT_DEPENDENCIAS','SIEVAD_CAT_DEPENDENCIAS.DEPEN_ID','=',
                                                                          'SIEVAD_DPA.DEPEN_ID2')
                       ->select(    'SIEVAD_DPA.FOLIO', 
                                    'SIEVAD_DPA.PARTIDA', 
                                    'SIEVAD_DPA.CIPREP_ID',
                                    'SIEVAD_DPA.LGOB_COD',
                                    'SIEVAD_DPA.DEPEN_ID1',
                                    'SIEVAD_DPA.DEPEN_ID2',
                                    'SIEVAD_CAT_DEPENDENCIAS.DEPEN_DESC', 
                                    'SIEVAD_DPA.MES_ID2', 
                                    'SIEVAD_DPA.FECHA_ENTREGA', 
                                    'SIEVAD_DPA.FECHA_ENTREGA2', 
                                    'SIEVAD_DPA.FECHA_ENTREGA3', 
                                    'SIEVAD_DPA.ACTIVIDAD_DESC', 
                                    'SIEVAD_DPA.OBJETIVO_DESC', 
                                    'SIEVAD_DPA.OPERACIONAL_DESC', 
                                    'SIEVAD_DPA.SOPORTE_01',
                                    'SIEVAD_DPA.OBS_01',
                                    
                                    'SIEVAD_DPA.UMED_ID', 
                                    'SIEVAD_CAT_UMEDIDA.UMED_DESC', 
                                    'SIEVAD_DPA.MESP_01', 'SIEVAD_DPA.MESP_02', 'SIEVAD_DPA.MESP_03', 
                                    'SIEVAD_DPA.MESP_04', 'SIEVAD_DPA.MESP_05', 'SIEVAD_DPA.MESP_06', 
                                    'SIEVAD_DPA.MESP_07', 'SIEVAD_DPA.MESP_08', 'SIEVAD_DPA.MESP_09', 
                                    'SIEVAD_DPA.MESP_10', 'SIEVAD_DPA.MESP_11', 'SIEVAD_DPA.MESP_12',
                                    'SIEVAD_DPA.MES_P01','SIEVAD_DPA.MES_P02','SIEVAD_DPA.MES_P03','SIEVAD_DPA.MES_P04','SIEVAD_DPA.MES_P05','SIEVAD_DPA.MES_P06',
                                    'SIEVAD_DPA.MES_P07','SIEVAD_DPA.MES_P08','SIEVAD_DPA.MES_P09','SIEVAD_DPA.MES_P10','SIEVAD_DPA.MES_P11','SIEVAD_DPA.MES_P12',
                                    'SIEVAD_DPA.TRIM_P01','SIEVAD_DPA.TRIM_P02','SIEVAD_DPA.TRIM_P03','SIEVAD_DPA.TRIM_P04','SIEVAD_DPA.SEM_P01','SIEVAD_DPA.SEM_P02','SIEVAD_DPA.TOT_P01',
                                    'SIEVAD_DPA.SEMAF_01','SIEVAD_DPA.SEMAF_02','SIEVAD_DPA.SEMAF_03','SIEVAD_DPA.SEMAF_04','SIEVAD_DPA.SEMAF_05','SIEVAD_DPA.SEMAF_06',
                                    'SIEVAD_DPA.SEMAF_07','SIEVAD_DPA.SEMAF_08','SIEVAD_DPA.SEMAF_09','SIEVAD_DPA.SEMAF_10','SIEVAD_DPA.SEMAF_11','SIEVAD_DPA.SEMAF_12',
                                    'SIEVAD_DPA.SEMAFT_01','SIEVAD_DPA.SEMAFT_02','SIEVAD_DPA.SEMAFT_03','SIEVAD_DPA.SEMAFT_04','SIEVAD_DPA.SEMAFS_01','SIEVAD_DPA.SEMAFS_02','SIEVAD_DPA.SEMAFA_01'
                               )
                       ->selectRaw('(SIEVAD_DPA.MESP_01+SIEVAD_DPA.MESP_02+SIEVAD_DPA.MESP_03+
                                     SIEVAD_DPA.MESP_04+SIEVAD_DPA.MESP_05+SIEVAD_DPA.MESP_06+
                                     SIEVAD_DPA.MESP_07+SIEVAD_DPA.MESP_08+SIEVAD_DPA.MESP_09+
                                     SIEVAD_DPA.MESP_10+SIEVAD_DPA.MESP_11+SIEVAD_DPA.MESP_12)
                                     META_PROGRAMADA')
                       ->where(     'SIEVAD_DPA.FOLIO'     ,$id2)
                       ->orderBy(   'SIEVAD_DPA.PERIODO_ID','ASC')                   
                       ->orderBy(   'SIEVAD_DPA.FOLIO'     ,'ASC')
                       ->orderBy(   'SIEVAD_DPA.PARTIDA'   ,'ASC')
                ->get();    
        //dd('Llave:',$id,' llave2:',$id2);       
        if($regprogdanual->count() <= 0){ 
            toastr()->error('No existen registros de acciones o metas del Programa anual.','Uppss!',['positionClass' => 'toast-bottom-right']);
        }else{
            $pdf = PDF::loadView('sicinar.pdf.ProgAnualPdf',compact('nombre','usuario','regumedida','regdepen','regprogeanual','regprogdanual','regepproy','regepprog','regmeses','regtipometa'));
            //$options = new Options();
            //$options->set('defaultFont', 'Courier');
            //$pdf->set_option('defaultFont', 'Courier');
            $pdf->setPaper('A4', 'landscape');      
            //$pdf->set('defaultFont', 'Courier');          
            //$pdf->setPaper('A4','portrait');

            // Output the generated PDF to Browser
            return $pdf->stream('Programa_Anual-'.$id2);
        }
    }


    // Gráfica por estado
    public function IapxEdo(){
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

        $regtotxedo=regdepenModel::join('JP_CAT_ENTIDADES_FED',[['JP_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_ID','=','SIEVAD_CAT_DEPENDENCIAS.ENTIDADFEDERATIVA_ID'],['SIEVAD_CAT_DEPENDENCIAS.DEPEN_ID','<>',0]])
                         ->selectRaw('COUNT(*) AS TOTALXEDO')
                               ->get();

        $regdepen=regdepenModel::join('JP_CAT_ENTIDADES_FED',[['JP_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_ID','=','SIEVAD_CAT_DEPENDENCIAS.ENTIDADFEDERATIVA_ID'],['SIEVAD_CAT_DEPENDENCIAS.DEPEN_ID','<>',0]])
                      ->selectRaw('SIEVAD_CAT_DEPENDENCIAS.ENTIDADFEDERATIVA_ID, JP_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_DESC AS ESTADO, COUNT(*) AS TOTAL')
                        ->groupBy('SIEVAD_CAT_DEPENDENCIAS.ENTIDADFEDERATIVA_ID', 'JP_CAT_ENTIDADES_FED.ENTIDADFEDERATIVA_DESC')
                        ->orderBy('SIEVAD_CAT_DEPENDENCIAS.ENTIDADFEDERATIVA_ID','asc')
                        ->get();
        //$procesos = procesosModel::join('SCI_TIPO_PROCESO','SCI_PROCESOS.CVE_TIPO_PROC','=','SCI_TIPO_PROCESO.CVE_TIPO_PROC')
        //    ->selectRaw('SCI_TIPO_PROCESO.DESC_TIPO_PROC AS TIPO, COUNT(SCI_PROCESOS.CVE_TIPO_PROC) AS TOTAL')
        //    ->groupBy('SCI_TIPO_PROCESO.DESC_TIPO_PROC')
        //    ->get();
        //dd($procesos);
        return view('sicinar.numeralia.iapxedo',compact('regdepen','regtotxedo','nombre','usuario','rango'));
    }


    // Gráfica demanda de transacciones (Bitacora)
    public function Bitacora(){
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

        // http://www.chartjs.org/docs/#bar-chart
        $regbitatxmes=regBitacoraModel::join('JP_CAT_PROCESOS','JP_CAT_PROCESOS.PROCESO_ID' ,'=','JP_BITACORA.PROCESO_ID')
                                   ->join('JP_CAT_FUNCIONES','JP_CAT_FUNCIONES.FUNCION_ID','=','JP_BITACORA.FUNCION_ID')
                                   ->join('JP_CAT_TRX'      ,'JP_CAT_TRX.TRX_ID'          ,'=','JP_BITACORA.TRX_ID')
                                   ->join('JP_CAT_MESES'    ,'JP_CAT_MESES.MES_ID'        ,'=','JP_BITACORA.MES_ID')
                         ->select('JP_BITACORA.MES_ID','JP_CAT_MESES.MES_DESC')
                         ->selectRaw('COUNT(*) AS TOTALGENERAL')
                         ->groupBy('JP_BITACORA.MES_ID','JP_CAT_MESES.MES_DESC')
                         ->orderBy('JP_BITACORA.MES_ID','asc')
                         ->get();        
        $regbitatot=regBitacoraModel::join('JP_CAT_PROCESOS','JP_CAT_PROCESOS.PROCESO_ID' ,'=','JP_BITACORA.PROCESO_ID')
                                   ->join('JP_CAT_FUNCIONES','JP_CAT_FUNCIONES.FUNCION_ID','=','JP_BITACORA.FUNCION_ID')
                                   ->join('JP_CAT_TRX'      ,'JP_CAT_TRX.TRX_ID'          ,'=','JP_BITACORA.TRX_ID')
                         ->selectRaw('SUM(CASE WHEN JP_BITACORA.MES_ID = 1 THEN 1 END) AS M01')  
                         ->selectRaw('SUM(CASE WHEN JP_BITACORA.MES_ID = 2 THEN 1 END) AS M02')
                         ->selectRaw('SUM(CASE WHEN JP_BITACORA.MES_ID = 3 THEN 1 END) AS M03')
                         ->selectRaw('SUM(CASE WHEN JP_BITACORA.MES_ID = 4 THEN 1 END) AS M04')
                         ->selectRaw('SUM(CASE WHEN JP_BITACORA.MES_ID = 5 THEN 1 END) AS M05')
                         ->selectRaw('SUM(CASE WHEN JP_BITACORA.MES_ID = 6 THEN 1 END) AS M06')
                         ->selectRaw('SUM(CASE WHEN JP_BITACORA.MES_ID = 7 THEN 1 END) AS M07')
                         ->selectRaw('SUM(CASE WHEN JP_BITACORA.MES_ID = 8 THEN 1 END) AS M08')
                         ->selectRaw('SUM(CASE WHEN JP_BITACORA.MES_ID = 9 THEN 1 END) AS M09')
                         ->selectRaw('SUM(CASE WHEN JP_BITACORA.MES_ID =10 THEN 1 END) AS M10')
                         ->selectRaw('SUM(CASE WHEN JP_BITACORA.MES_ID =11 THEN 1 END) AS M11')
                         ->selectRaw('SUM(CASE WHEN JP_BITACORA.MES_ID =12 THEN 1 END) AS M12')
                         ->selectRaw('COUNT(*) AS TOTALGENERAL')
                         ->get();

        $regbitacora=regBitacoraModel::join('JP_CAT_PROCESOS' ,'JP_CAT_PROCESOS.PROCESO_ID' ,'=','JP_BITACORA.PROCESO_ID')
                                     ->join('JP_CAT_FUNCIONES','JP_CAT_FUNCIONES.FUNCION_ID','=','JP_BITACORA.FUNCION_ID')
                                     ->join('JP_CAT_TRX'      ,'JP_CAT_TRX.TRX_ID'          ,'=','JP_BITACORA.TRX_ID')
                    ->select('JP_BITACORA.PERIODO_ID', 'JP_BITACORA.PROGRAMA_ID', 'JP_BITACORA.PROCESO_ID', 
                                'JP_CAT_PROCESOS.PROCESO_DESC', 'JP_BITACORA.FUNCION_ID', 'JP_CAT_FUNCIONES.FUNCION_DESC', 
                                'JP_BITACORA.TRX_ID', 'JP_CAT_TRX.TRX_DESC')
                    ->selectRaw('SUM(CASE WHEN JP_BITACORA.MES_ID = 1 THEN 1 END) AS ENE')  
                    ->selectRaw('SUM(CASE WHEN JP_BITACORA.MES_ID = 2 THEN 1 END) AS FEB')
                    ->selectRaw('SUM(CASE WHEN JP_BITACORA.MES_ID = 3 THEN 1 END) AS MAR')
                    ->selectRaw('SUM(CASE WHEN JP_BITACORA.MES_ID = 4 THEN 1 END) AS ABR')
                    ->selectRaw('SUM(CASE WHEN JP_BITACORA.MES_ID = 5 THEN 1 END) AS MAY')
                    ->selectRaw('SUM(CASE WHEN JP_BITACORA.MES_ID = 6 THEN 1 END) AS JUN')
                    ->selectRaw('SUM(CASE WHEN JP_BITACORA.MES_ID = 7 THEN 1 END) AS JUL')
                    ->selectRaw('SUM(CASE WHEN JP_BITACORA.MES_ID = 8 THEN 1 END) AS AGO')
                    ->selectRaw('SUM(CASE WHEN JP_BITACORA.MES_ID = 9 THEN 1 END) AS SEP')
                    ->selectRaw('SUM(CASE WHEN JP_BITACORA.MES_ID =10 THEN 1 END) AS OCT')
                    ->selectRaw('SUM(CASE WHEN JP_BITACORA.MES_ID =11 THEN 1 END) AS NOV')
                    ->selectRaw('SUM(CASE WHEN JP_BITACORA.MES_ID =12 THEN 1 END) AS DIC')                   
                    ->selectRaw('COUNT(*) AS SUMATOTAL')
                    ->groupBy('JP_BITACORA.PERIODO_ID', 'JP_BITACORA.PROGRAMA_ID','JP_BITACORA.PROCESO_ID', 
                              'JP_CAT_PROCESOS.PROCESO_DESC','JP_BITACORA.FUNCION_ID','JP_CAT_FUNCIONES.FUNCION_DESC', 
                              'JP_BITACORA.TRX_ID', 'JP_CAT_TRX.TRX_DESC')
                    ->orderBy('JP_BITACORA.PERIODO_ID', 'JP_BITACORA.PROGRAMA_ID','JP_BITACORA.PROCESO_ID', 
                              'JP_CAT_PROCESOS.PROCESO_DESC','JP_BITACORA.FUNCION_ID','JP_CAT_FUNCIONES.FUNCION_DESC',
                              'JP_BITACORA.TRX_ID', 'JP_CAT_TRX.TRX_DESC','asc')
                    ->get();
        //dd($procesos);
        return view('sicinar.numeralia.bitacora',compact('regbitatxmes','regbitacora','regbitatot','nombre','usuario','rango'));
    }

    // Gráfica de IAP por municipio
    public function IapxMpio(){
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

        $regtotxmpio=regdepenModel::join('JP_CAT_MUNICIPIOS_SEDESEM',[['JP_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID','=',15],
                                                            ['JP_CAT_MUNICIPIOS_SEDESEM.MUNICIPIOID','=','SIEVAD_CAT_DEPENDENCIAS.MUNICIPIO_ID'],['SIEVAD_CAT_DEPENDENCIAS.DEPEN_ID','<>',0]])
                         ->selectRaw('COUNT(*) AS TOTALXMPIO')
                               ->get();
        $regdepen=regdepenModel::join('JP_CAT_MUNICIPIOS_SEDESEM',[['JP_CAT_MUNICIPIOS_SEDESEM.ENTIDADFEDERATIVAID','=',15],
                                                            ['JP_CAT_MUNICIPIOS_SEDESEM.MUNICIPIOID','=','SIEVAD_CAT_DEPENDENCIAS.MUNICIPIO_ID'],['SIEVAD_CAT_DEPENDENCIAS.DEPEN_ID','<>',0]])
                      ->selectRaw('SIEVAD_CAT_DEPENDENCIAS.MUNICIPIO_ID, JP_CAT_MUNICIPIOS_SEDESEM.MUNICIPIONOMBRE AS MUNICIPIO,COUNT(*) AS TOTAL')
                        ->groupBy('SIEVAD_CAT_DEPENDENCIAS.MUNICIPIO_ID', 'JP_CAT_MUNICIPIOS_SEDESEM.MUNICIPIONOMBRE')
                        ->orderBy('SIEVAD_CAT_DEPENDENCIAS.MUNICIPIO_ID','asc')
                        ->get();
        //dd($procesos);
        return view('sicinar.numeralia.iapxmpio',compact('regdepen','regtotxmpio','nombre','usuario','rango'));
    }

    // Gráfica de IAP por Rubro social
    public function IapxRubro(){
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

        $regtotxrubro=regdepenModel::join('JP_CAT_RUBROS','JP_CAT_RUBROS.UMEDIDA_ID','=','SIEVAD_CAT_DEPENDENCIAS.UMEDIDA_ID')
                      ->selectRaw('COUNT(*) AS TOTALXRUBRO')
                            ->get();
        $regdepen=regdepenModel::join('JP_CAT_RUBROS','JP_CAT_RUBROS.UMEDIDA_ID','=','SIEVAD_CAT_DEPENDENCIAS.UMEDIDA_ID')
                      ->selectRaw('SIEVAD_CAT_DEPENDENCIAS.UMEDIDA_ID,  JP_CAT_RUBROS.RUBRO_DESC AS RUBRO, COUNT(*) AS TOTAL')
                        ->groupBy('SIEVAD_CAT_DEPENDENCIAS.UMEDIDA_ID','JP_CAT_RUBROS.RUBRO_DESC')
                        ->orderBy('SIEVAD_CAT_DEPENDENCIAS.UMEDIDA_ID','asc')
                        ->get();
        //$procesos = procesosModel::join('SCI_TIPO_PROCESO','SCI_PROCESOS.CVE_TIPO_PROC','=','SCI_TIPO_PROCESO.CVE_TIPO_PROC')
        //    ->selectRaw('SCI_TIPO_PROCESO.DESC_TIPO_PROC AS TIPO, COUNT(SCI_PROCESOS.CVE_TIPO_PROC) AS TOTAL')
        //    ->groupBy('SCI_TIPO_PROCESO.DESC_TIPO_PROC')
        //    ->get();
        //dd($procesos);
        return view('sicinar.numeralia.iapxrubro',compact('regdepen','regtotxrubro','nombre','usuario','rango'));
    }

    // Gráfica de IAP por Rubro social
    public function IapxRubro2(){
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

        $regtotxrubro=regdepenModel::join('JP_CAT_RUBROS','JP_CAT_RUBROS.UMEDIDA_ID','=','SIEVAD_CAT_DEPENDENCIAS.UMEDIDA_ID')
                      ->selectRaw('COUNT(*) AS TOTALXRUBRO')
                            ->get();
        $regdepen=regdepenModel::join('JP_CAT_RUBROS','JP_CAT_RUBROS.UMEDIDA_ID','=','SIEVAD_CAT_DEPENDENCIAS.UMEDIDA_ID')
                      ->selectRaw('SIEVAD_CAT_DEPENDENCIAS.UMEDIDA_ID,  JP_CAT_RUBROS.RUBRO_DESC AS RUBRO, COUNT(*) AS TOTAL')
                        ->groupBy('SIEVAD_CAT_DEPENDENCIAS.UMEDIDA_ID','JP_CAT_RUBROS.RUBRO_DESC')
                        ->orderBy('SIEVAD_CAT_DEPENDENCIAS.UMEDIDA_ID','asc')
                        ->get();
        //$procesos = procesosModel::join('SCI_TIPO_PROCESO','SCI_PROCESOS.CVE_TIPO_PROC','=','SCI_TIPO_PROCESO.CVE_TIPO_PROC')
        //    ->selectRaw('SCI_TIPO_PROCESO.DESC_TIPO_PROC AS TIPO, COUNT(SCI_PROCESOS.CVE_TIPO_PROC) AS TOTAL')
        //    ->groupBy('SCI_TIPO_PROCESO.DESC_TIPO_PROC')
        //    ->get();
        //dd($procesos);
        return view('sicinar.numeralia.graficadeprueba',compact('regdepen','regtotxrubro','nombre','usuario','rango'));
    }

    // Mapas
    public function Mapas(){
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

        $regtotxrubro=regdepenModel::join('JP_CAT_RUBROS','JP_CAT_RUBROS.UMEDIDA_ID','=','SIEVAD_CAT_DEPENDENCIAS.UMEDIDA_ID')
                      ->selectRaw('COUNT(*) AS TOTALXRUBRO')
                            ->get();

        $regdepen=regdepenModel::join('JP_CAT_RUBROS','JP_CAT_RUBROS.UMEDIDA_ID','=','SIEVAD_CAT_DEPENDENCIAS.UMEDIDA_ID')
                      ->selectRaw('SIEVAD_CAT_DEPENDENCIAS.UMEDIDA_ID,  JP_CAT_RUBROS.RUBRO_DESC AS RUBRO, COUNT(*) AS TOTAL')
                        ->groupBy('SIEVAD_CAT_DEPENDENCIAS.UMEDIDA_ID','JP_CAT_RUBROS.RUBRO_DESC')
                        ->orderBy('SIEVAD_CAT_DEPENDENCIAS.UMEDIDA_ID','asc')
                        ->get();
        //$procesos = procesosModel::join('SCI_TIPO_PROCESO','SCI_PROCESOS.CVE_TIPO_PROC','=','SCI_TIPO_PROCESO.CVE_TIPO_PROC')
        //    ->selectRaw('SCI_TIPO_PROCESO.DESC_TIPO_PROC AS TIPO, COUNT(SCI_PROCESOS.CVE_TIPO_PROC) AS TOTAL')
        //    ->groupBy('SCI_TIPO_PROCESO.DESC_TIPO_PROC')
        //    ->get();
        //dd($procesos);
        return view('sicinar.numeralia.mapasdeprueba',compact('regdepen','regtotxrubro','nombre','usuario','rango'));
    }

    // Mapas
    public function Mapas2(){
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

        $regtotxrubro=regdepenModel::join('JP_CAT_RUBROS','JP_CAT_RUBROS.UMEDIDA_ID','=','SIEVAD_CAT_DEPENDENCIAS.UMEDIDA_ID')
                      ->selectRaw('COUNT(*) AS TOTALXRUBRO')
                            ->get();

        $regdepen=regdepenModel::join('JP_CAT_RUBROS','JP_CAT_RUBROS.UMEDIDA_ID','=','SIEVAD_CAT_DEPENDENCIAS.UMEDIDA_ID')
                      ->selectRaw('SIEVAD_CAT_DEPENDENCIAS.UMEDIDA_ID,  JP_CAT_RUBROS.RUBRO_DESC AS RUBRO, COUNT(*) AS TOTAL')
                        ->groupBy('SIEVAD_CAT_DEPENDENCIAS.UMEDIDA_ID','JP_CAT_RUBROS.RUBRO_DESC')
                        ->orderBy('SIEVAD_CAT_DEPENDENCIAS.UMEDIDA_ID','asc')
                        ->get();
        //$procesos = procesosModel::join('SCI_TIPO_PROCESO','SCI_PROCESOS.CVE_TIPO_PROC','=','SCI_TIPO_PROCESO.CVE_TIPO_PROC')
        //    ->selectRaw('SCI_TIPO_PROCESO.DESC_TIPO_PROC AS TIPO, COUNT(SCI_PROCESOS.CVE_TIPO_PROC) AS TOTAL')
        //    ->groupBy('SCI_TIPO_PROCESO.DESC_TIPO_PROC')
        //    ->get();
        //dd($procesos);
        return view('sicinar.numeralia.mapasdeprueba2',compact('regdepen','regtotxrubro','nombre','usuario','rango'));
    }

    // Mapas
    public function Mapas3(){
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

        $regtotxrubro=regdepenModel::join('JP_CAT_RUBROS','JP_CAT_RUBROS.UMEDIDA_ID','=','SIEVAD_CAT_DEPENDENCIAS.UMEDIDA_ID')
                      ->selectRaw('COUNT(*) AS TOTALXRUBRO')
                            ->get();
        $regdepen=regdepenModel::join('JP_CAT_RUBROS','JP_CAT_RUBROS.UMEDIDA_ID','=','SIEVAD_CAT_DEPENDENCIAS.UMEDIDA_ID')
                      ->selectRaw('SIEVAD_CAT_DEPENDENCIAS.UMEDIDA_ID,  JP_CAT_RUBROS.RUBRO_DESC AS RUBRO, COUNT(*) AS TOTAL')
                        ->groupBy('SIEVAD_CAT_DEPENDENCIAS.UMEDIDA_ID','JP_CAT_RUBROS.RUBRO_DESC')
                        ->orderBy('SIEVAD_CAT_DEPENDENCIAS.UMEDIDA_ID','asc')
                        ->get();
        //dd($procesos);
        return view('sicinar.numeralia.mapasdeprueba3',compact('regdepen','regtotxrubro','nombre','usuario','rango'));
    }

    //*****************************************************************************//
    //*************************************** Detalle *****************************//
    //*****************************************************************************//
    public function actionVerdPA($id){
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
        $regtipometa  = regTipometaModel::select('TACCION_ID','TACCION_DESC')
                        ->orderBy('TACCION_ID','asc')
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
                        'DEPEN_ID1','DEPEN_ID2','DEPEN_ID3','EPPROG_ID','EPPROY_ID','TACCION_ID',
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
                        'SOPORTE_ID','SOPORTE_01','SOPORTE_02','SOPORTE_03','SOPORTE_04','OBS_01','OBS_02',
                        'STATUS_1','STATUS_2','FECREG','FECREG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')        
                        ->where(  'FOLIO'  ,$id)            
                        ->orderBy('FOLIO'  ,'asc')
                        ->orderBy('PARTIDA','asc')
                        ->paginate(30);           
        if($regprogdanual->count() <= 0){
            toastr()->error('No existen acciones o metas del Programa anual.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }                        
        return view('sicinar.programa_anual.verdProganual',compact('nombre','usuario','regdepen','regtipometa','regumedida','reganios','regperiodos','regmeses','regdias','regprogeanual','regprogdanual','regepprog','regepproy'));
    }

    public function actionNuevodPA($id){
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
        $regmeses     = regMesesModel::select('MES_ID','MES_DESC')
                        ->get();            
        $regtipometa  = regTipometaModel::select('TACCION_ID','TACCION_DESC')
                        ->orderBy('TACCION_ID','asc')
                        ->get();                                           
        $regepprog    = regEPprogramaModel::select('EPPROG_ID','EPPROG_DESC')
                        ->orderBy('EPPROG_DESC','asc')
                        ->get();                         
        $regepproy    = regEPproyectoModel::select('EPPROY_ID','EPPROY_DESC')
                        ->orderBy('EPPROY_DESC','asc')
                        ->get();                   
        if(session()->get('rango') !== '0'){                           
            $regdepen   = regdepenModel::select('DEPEN_ID', 'DEPEN_DESC')
                          ->where('DEPEN_STATUS','1')
                          ->orderBy('DEPEN_ID','ASC')
                          ->get();                                                        
        }else{
            $regdepen   = regdepenModel::select('DEPEN_ID', 'DEPEN_DESC')
                          ->where('DEPEN_STATUS','1')
                          ->where('DEPEN_ID',$depen_id)
                          ->orderBy('DEPEN_ID','ASC')
                          ->get();            
        }    
        $regprogeanual  = regProgeAnualModel::select('FOLIO','PERIODO_ID','DEPEN_ID1','DEPEN_ID2','DEPEN_ID3',
                          'EPPROG_ID','EPPROY_ID','FECHA_ENTREGA','FECHA_ENTREGA2','FECHA_ENTREGA3',
                          'PERIODO_ID1','MES_ID1','DIA_ID1','MES_ID2','PROGRAMA_ID','PROGRAMA_DESC',
                          'RESPONSABLE','ELABORO','AUTORIZO','OBS_1','OBS_2','STATUS_1','STATUS_2',
                          'FECREG','FECREG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')
                          ->where(  'FOLIO'     ,$id  )            
                          ->orderBy('PERIODO_ID','asc') 
                          ->orderBy('DEPEN_ID1' ,'asc') 
                          ->orderBy('FOLIO'     ,'asc')                        
                          ->get();
        $regprogdanual = regProgdAnualModel::select('FOLIO','PARTIDA','PERIODO_ID','CIPREP_ID','LGOB_COD',
                         'DEPEN_ID1','DEPEN_ID2','DEPEN_ID3','EPPROG_ID','EPPROY_ID','TACCION_ID',
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
                         'SOPORTE_ID','SOPORTE_01','SOPORTE_02','SOPORTE_03','SOPORTE_04','OBS_01','OBS_02',
                         'STATUS_1','STATUS_2','FECREG','FECREG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')        
                         ->where(  'FOLIO'     ,$id  )    
                         ->orderBy('PERIODO_ID','asc') 
                         ->orderBy('DEPEN_ID1' ,'asc') 
                         ->orderBy('FOLIO'     ,'asc')
                         ->orderBy('PARTIDA'   ,'asc')
                         ->get();                                
        //dd($unidades);
        return view('sicinar.programa_anual.nuevodProganual',compact('nombre','usuario','regdepen','regtipometa','regumedida','regprogeanual','regprogdanual','regepproy','regepprog','regmeses'));
    }

    public function actionAltaNuevodPA(Request $request){
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
        $depen_id     = session()->get('depen_id');        

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
        //$duplicado = regProgdAnualModel::where(['PERIODO_ID' => $request->periodo_id,
        //                                       'DEPEN_ID'     => $request->DEPEN_ID, 
        //                                       'FOLIO'      => $request->folio])
        //             ->get();
        //if($duplicado->count() <= 0 )
        //    return back()->withInput()->withErrors(['DEPEN_ID' => 'IAP '.$request->DEPEN_ID.' Ya existe Programa anual en el mismo periodo y con la IAP referida. Por favor verificar.']);
        //else{  

            /******************* Alta **********************/ 
            //$mes1 = regMesesModel::ObtMes($request->mes_id1);
            //$dia1 = regDiasModel::ObtDia($request->dia_id1);   

            // ******** Obtiene partida ************************/
            $partida = regProgdAnualModel::where(['PERIODO_ID'=> $request->periodo_id, 
                                                  'FOLIO'     => $request->folio])
                       ->max('PARTIDA');
                       //          'DEPEN_ID1' => $request->depen_id1, 
            $partida = $partida + 1;

            $file01 =null;
            if(isset($request->soporte_01)){
                if(!empty($request->soporte_01)){
                    if($request->hasFile('soporte_01')){
                        $file01=$request->periodo_id.'_'.$request->folio.'_'.$partida.'_'.$request->file('soporte_01')->getClientOriginalName();
                        //sube el archivo a la carpeta del servidor public/images/
                        $request->file('soporte_01')->move(public_path().'/storage/', $file01);
                    }
                }
            }
            //**************** Meta por mes *******************************//
            $mesp_01 = 0;
            if(isset($request->mesp_01)){
                if(!empty($request->mesp_01)) 
                    $mesp_01 = (float)$request->mesp_01;
            }          
            $mesp_02 = 0;
            if(isset($request->mesp_02)){
                if(!empty($request->mesp_02)) 
                    $mesp_02 = (float)$request->mesp_02;
            }  
            $mesp_03 = 0;
            if(isset($request->mesp_03)){
                if(!empty($request->mesp_03)) 
                    $mesp_03 = (float)$request->mesp_03;
            }  
            $mesp_04 = 0;
            if(isset($request->mesp_04)){
                if(!empty($request->mesp_04)) 
                    $mesp_04 = (float)$request->mesp_04;
            }  
            $mesp_05 = 0;
            if(isset($request->mesp_05)){
                if(!empty($request->mesp_05)) 
                    $mesp_05 = (float)$request->mesp_05;
            }                                                  
            $mesp_06 = 0;
            if(isset($request->mesp_06)){
                if(!empty($request->mesp_06)) 
                    $mesp_06 = (float)$request->mesp_06;
            }               
            $mesp_07 = 0;
            if(isset($request->mesp_07)){
                if(!empty($request->mesp_07)) 
                    $mesp_07 = (float)$request->mesp_07;
            }          
            $mesp_08 = 0;
            if(isset($request->mesp_08)){
                if(!empty($request->mesp_08)) 
                    $mesp_08 = (float)$request->mesp_08;
            }  
            $mesp_09 = 0;
            if(isset($request->mesp_09)){
                if(!empty($request->mesp_09)) 
                    $mesp_09 = (float)$request->mesp_09;
            }  
            $mesp_10 = 0;
            if(isset($request->mesp_10)){
                if(!empty($request->mesp_10)) 
                    $mesp_10 = (float)$request->mesp_10;
            }  
            $mesp_11 = 0;
            if(isset($request->mesp_11)){
                if(!empty($request->mesp_11)) 
                    $mesp_11 = (float)$request->mesp_11;
            }                                                  
            $mesp_12 = 0;
            if(isset($request->mesp_12)){
                if(!empty($request->mesp_12)) 
                    $mesp_12 = (float)$request->mesp_12;
            }                           
            $trimp_01 = (float)$mesp_01 + (float)$mesp_02 + (float)$mesp_03;
            $trimp_02 = (float)$mesp_04 + (float)$mesp_05 + (float)$mesp_06;
            $trimp_03 = (float)$mesp_07 + (float)$mesp_08 + (float)$mesp_09;
            $trimp_04 = (float)$mesp_10 + (float)$mesp_11 + (float)$mesp_12;

            $tsemp_01 = (float)$mesp_01 + (float)$mesp_02 + (float)$mesp_03+(float)$mesp_04+(float)$mesp_05+(float)$mesp_06;
            $tsemp_02 = (float)$mesp_07 + (float)$mesp_08 + (float)$mesp_09+(float)$mesp_10+(float)$mesp_11+(float)$mesp_12;            

            //**************** Meta total *******************************//
            $totp_01 = 0;
            if(isset($request->totp_01)){
                if(!empty($request->totp_01)) 
                    $totp_01 = (float)$request->totp_01;
            }          
            //*************** Meta total calculada **********************//
            $totp_02  = (float)$tsemp_01 + (float)$tsemp_02;                        

            $nuevoprogdtrab = new regProgdAnualModel();

            $nuevoprogdtrab->CIPREP_ID      = $request->ciprep_id;
            $nuevoprogdtrab->LGOB_COD       = $request->lgob_cod;
            $nuevoprogdtrab->FOLIO          = $request->folio;
            $nuevoprogdtrab->PARTIDA        = $partida;
            $nuevoprogdtrab->PERIODO_ID     = $request->periodo_id;                            
            $nuevoprogdtrab->DEPEN_ID1      = $request->depen_id1;
            $nuevoprogdtrab->DEPEN_ID2      = $request->depen_id2;            
            $nuevoprogdtrab->EPPROG_ID      = $request->epprog_id;
            $nuevoprogdtrab->EPPROY_ID      = $request->epproy_id;             
            $nuevoprogdtrab->FECHA_ENTREGA  = $request->fecha_entrega; 
            $nuevoprogdtrab->FECHA_ENTREGA2 = $request->fecha_entrega2;
            $nuevoprogdtrab->FECHA_ENTREGA3 = $request->fecha_entrega3;

            $nuevoprogdtrab->ACTIVIDAD_DESC = substr(trim(strtoupper($request->actividad_desc))  ,0,3999);
            $nuevoprogdtrab->OBJETIVO_DESC  = substr(trim(strtoupper($request->objetivo_desc))   ,0,3999);
            $nuevoprogdtrab->OPERACIONAL_DESC=substr(trim(strtoupper($request->operacional_desc)),0,3999);
            $nuevoprogdtrab->UMED_ID        = $request->umed_id;
            $nuevoprogdtrab->TACCION_ID     = $request->taccion_id;
            $nuevoprogdtrab->MESP_01        = $request->mesp_01;
            $nuevoprogdtrab->MESP_02        = $request->mesp_02;
            $nuevoprogdtrab->MESP_03        = $request->mesp_03;
            $nuevoprogdtrab->MESP_04        = $request->mesp_04;
            $nuevoprogdtrab->MESP_05        = $request->mesp_05;
            $nuevoprogdtrab->MESP_06        = $request->mesp_06;
            $nuevoprogdtrab->MESP_07        = $request->mesp_07;
            $nuevoprogdtrab->MESP_08        = $request->mesp_08;
            $nuevoprogdtrab->MESP_09        = $request->mesp_09;
            $nuevoprogdtrab->MESP_10        = $request->mesp_10;
            $nuevoprogdtrab->MESP_11        = $request->mesp_11;
            $nuevoprogdtrab->MESP_12        = $request->mesp_12;

            $nuevoprogdtrab->TRIMP_01       = $trimp_01;
            $nuevoprogdtrab->TRIMP_02       = $trimp_02;
            $nuevoprogdtrab->TRIMP_03       = $trimp_03;
            $nuevoprogdtrab->TRIMP_04       = $trimp_04;

            $nuevoprogdtrab->TSEMP_01       = $tsemp_01;
            $nuevoprogdtrab->TSEMP_02       = $tsemp_02;

            $nuevoprogdtrab->TOTP_01        = $totp_01;
            $nuevoprogdtrab->TOTP_02        = $totp_02;

            $nuevoprogdtrab->SOPORTE_01     = $file01;
            $nuevoprogdtrab->OBS_01         = substr(trim(strtoupper($request->obs_01)),0,3999);
      
            $nuevoprogdtrab->IP             = $ip;
            $nuevoprogdtrab->LOGIN          = $nombre;         // Usuario ;
            $nuevoprogdtrab->save();
            if($nuevoprogdtrab->save() == true){
                toastr()->success('Acción o meta del Programa anual dado de alta.','ok!',['positionClass' => 'toast-bottom-right']);
                /************ Bitacora inicia *************************************/ 
                setlocale(LC_TIME, "spanish");        
                $xip          = session()->get('ip');
                $xperiodo_id  = (int)date('Y');
                $xprograma_id = 1;
                $xmes_id      = (int)date('m');
                $xproceso_id  =         3;
                $xfuncion_id  =      3011;
                $xtrx_id      =        42;    //Alta
                $regbitacora = regBitacoraModel::select('PERIODO_ID','MES_ID','PROCESO_ID','FUNCION_ID', 
                                                        'TRX_ID','FOLIO','NO_VECES','FECHA_REG','IP','LOGIN', 
                                                        'FECHA_M','IP_M','LOGIN_M')
                               ->where(['PERIODO_ID' => $xperiodo_id,'MES_ID'     => $xmes_id,
                                        'PROCESO_ID' => $xproceso_id,'FUNCION_ID' => $xfuncion_id,
                                        'TRX_ID'     => $xtrx_id    ,'FOLIO'      => $request->folio])
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
                        toastr()->success('Trx de act. Programa anual registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                    else
                        toastr()->error('Error de Trx de act. Programa anual. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
                }else{                   
                    //*********** Obtine el no. de veces *****************************
                    $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id,'MES_ID'     => $xmes_id, 
                                                          'PROCESO_ID' => $xproceso_id,'FUNCION_ID' => $xfuncion_id, 
                                                          'TRX_ID'     => $xtrx_id    ,'FOLIO'      => $request->folio])
                                 ->max('NO_VECES');
                    $xno_veces = $xno_veces+1;                        
                    //*********** Termina de obtener el no de veces *****************************         
                    $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                                   ->where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id,
                                            'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id,'FOLIO'      => $request->folio])
                                   ->update([
                                             'NO_VECES'=> $regbitacora->NO_VECES = $xno_veces,
                                             'IP_M'    => $regbitacora->IP       = $ip,
                                             'LOGIN_M' => $regbitacora->LOGIN_M  = $nombre,
                                             'FECHA_M' => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                            ]);
                    toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                }   /************ Bitacora termina *************************************/ 
            }else{
                toastr()->error('Error en Trx de act. Programa anual, Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
            }   //******************** Termina la alta ***************/
        //}     // ******************* Termina el duplicado **********/
        return redirect()->route('verdpa',$request->folio);
    }

    //******************* Editar *****************
    public function actionEditardPA($id, $id2){
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
        $regmeses     = regMesesModel::select('MES_ID','MES_DESC')
                        ->get();          
        $regtipometa  = regTipometaModel::select('TACCION_ID','TACCION_DESC')
                        ->orderBy('TACCION_ID','asc')
                        ->get();                                             
        $regepprog    = regEPprogramaModel::select('EPPROG_ID','EPPROG_DESC')
                        ->orderBy('EPPROG_ID','asc')
                        ->get();                         
        $regepproy    = regEPproyectoModel::select('EPPROY_ID','EPPROY_DESC')
                        ->orderBy('EPPROY_ID','asc')
                        ->get();                         
        //********* Validar rol de usuario **********************/
        if(session()->get('rango') !== '0'){                          
            $regdepen   = regdepenModel::select('DEPEN_ID', 'DEPEN_DESC')
                          ->where('DEPEN_STATUS','1')
                          ->get();                                                        
        }else{
            $regdepen   = regdepenModel::select('DEPEN_ID', 'DEPEN_DESC')
                          ->where('DEPEN_STATUS','1')
                          ->where('DEPEN_ID',$depen_id)
                          ->get();            
        }                    
        $regprogeanual  = regProgeAnualModel::select('FOLIO','PERIODO_ID','DEPEN_ID1','DEPEN_ID2','DEPEN_ID3',
                          'EPPROG_ID','EPPROY_ID','FECHA_ENTREGA','FECHA_ENTREGA2','FECHA_ENTREGA3',
                          'PERIODO_ID1','MES_ID1','DIA_ID1','MES_ID2','PROGRAMA_ID','PROGRAMA_DESC',
                          'RESPONSABLE','ELABORO','AUTORIZO','OBS_1','OBS_2','STATUS_1','STATUS_2',
                          'FECREG','FECREG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')
                          ->where(  'FOLIO'     ,$id)
                          ->orderBy('PERIODO_ID','ASC')
                          ->orderBy('FOLIO'     ,'ASC')
                          ->get();
        $regprogdanual  = regProgdAnualModel::select('FOLIO','PARTIDA','PERIODO_ID','CIPREP_ID','LGOB_COD',
                          'DEPEN_ID1','DEPEN_ID2','DEPEN_ID3','EPPROG_ID','EPPROY_ID','TACCION_ID',
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
                          'SOPORTE_ID','SOPORTE_01','SOPORTE_02','SOPORTE_03','SOPORTE_04','OBS_01','OBS_02',
                          'STATUS_1','STATUS_2','FECREG','FECREG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')        
                          ->where(['FOLIO' => $id, 'PARTIDA' => $id2])
                          //->where('FOLIO',$id)
                          //->where('PARTIDA',$id2)
                          ->first();
        if($regprogdanual->count() <= 0){
            toastr()->error('No existen registros de acciones o metas del Programa anual.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }
        return view('sicinar.programa_anual.editardProganual',compact('nombre','usuario','regtipometa','regdepen','reganios','regperiodos','regmeses','regdias','regprogeanual','regprogdanual','regumedida','regepproy','regepprog'));
    }

    public function actionActualizardPA(progdanualRequest $request, $id, $id2){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango');
        $ip            = session()->get('ip');
        $arbol_id      = session()->get('arbol_id');   
        $depen_id     = session()->get('depen_id');             

        // **************** actualizar ******************************
        $regprogdanual = regProgdAnualModel::where(['FOLIO' => $id, 'PARTIDA' => $id2]);
        if($regprogdanual->count() <= 0)
            toastr()->error('No existe acción o meta del Programa anual.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            //********************** Actualizar ********************************/
            //$mes1 = regMesesModel::ObtMes($request->mes_id1);
            //$dia1 = regDiasModel::ObtDia($request->dia_id1);                
            //dd('año 1:',$request->periodo_id1, ' año 2:',$request->periodo_id2,' mes1:',$mes1[0]->mes_mes,' dia1:',$dia1[0]->dia_desc,' mes2:',$mes2[0]->mes_mes, ' dia2:',$dia2[0]->dia_desc);
            //************** Valida meta total ************************//
            $totp_01 = 0;
            if(isset($request->totp_01)){
                if(!empty($request->totp_01)) 
                    $totp_01 = (float)$request->totp_01;
            }                      

            //************** Valida meta por mes **********************//
            $mesp_01 = 0;
            if(isset($request->mesp_01)){
                if(!empty($request->mesp_01)) 
                    $mesp_01 = (float)$request->mesp_01;
            }          
            $mesp_02 = 0;
            if(isset($request->mesp_02)){
                if(!empty($request->mesp_02)) 
                    $mesp_02 = (float)$request->mesp_02;
            }  
            $mesp_03 = 0;
            if(isset($request->mesp_03)){
                if(!empty($request->mesp_03)) 
                    $mesp_03 = (float)$request->mesp_03;
            }  
            $mesp_04 = 0;
            if(isset($request->mesp_04)){
                if(!empty($request->mesp_04)) 
                    $mesp_04 = (float)$request->mesp_04;
            }  
            $mesp_05 = 0;
            if(isset($request->mesp_05)){
                if(!empty($request->mesp_05)) 
                    $mesp_05 = (float)$request->mesp_05;
            }                                                  
            $mesp_06 = 0;
            if(isset($request->mesp_06)){
                if(!empty($request->mesp_06)) 
                    $mesp_06 = (float)$request->mesp_06;
            }               
            $mesp_07 = 0;
            if(isset($request->mesp_07)){
                if(!empty($request->mesp_07)) 
                    $mesp_07 = (float)$request->mesp_07;
            }          
            $mesp_08 = 0;
            if(isset($request->mesp_08)){
                if(!empty($request->mesp_08)) 
                    $mesp_08 = (float)$request->mesp_08;
            }  
            $mesp_09 = 0;
            if(isset($request->mesp_09)){
                if(!empty($request->mesp_09)) 
                    $mesp_09 = (float)$request->mesp_09;
            }  
            $mesp_10 = 0;
            if(isset($request->mesp_10)){
                if(!empty($request->mesp_10)) 
                    $mesp_10 = (float)$request->mesp_10;
            }  
            $mesp_11 = 0;
            if(isset($request->mesp_11)){
                if(!empty($request->mesp_11)) 
                    $mesp_11 = (float)$request->mesp_11;
            }                                                  
            $mesp_12 = 0;
            if(isset($request->mesp_12)){
                if(!empty($request->mesp_12)) 
                    $mesp_12 = (float)$request->mesp_12;
            }                           
            $trimp_01 = (float)$mesp_01 + (float)$mesp_02 + (float)$mesp_03;
            $trimp_02 = (float)$mesp_04 + (float)$mesp_05 + (float)$mesp_06;
            $trimp_03 = (float)$mesp_07 + (float)$mesp_08 + (float)$mesp_09;
            $trimp_04 = (float)$mesp_10 + (float)$mesp_11 + (float)$mesp_12;

            $tsemp_01 = (float)$trimp_01 + (float)$trimp_02;
            $tsemp_02 = (float)$trimp_03 + (float)$trimp_04;            

            $totp_02  = (float)$tsemp_01 + (float)$tsemp_02;                        

            $regprogdanual = regProgdAnualModel::where(['FOLIO' => $id, 'PARTIDA' => $id2])        
                             ->update([                
                                       'LGOB_COD'        => $request->lgob_cod,
                                       'CIPREP_ID'       => $request->ciprep_id,
                                       
                                       'ACTIVIDAD_DESC'  => substr(trim(strtoupper($request->actividad_desc))  ,0,3999),
                                       'OBJETIVO_DESC'   => substr(trim(strtoupper($request->objetivo_desc))   ,0,3999),
                                       'OPERACIONAL_DESC'=> substr(trim(strtoupper($request->operacional_desc)),0,3999),
                                       
                                       'UMED_ID'         => $request->umed_id,
                                       'TACCION_ID'      => $request->taccion_id,
                                       'MESP_01'         => $request->mesp_01,
                                       'MESP_02'         => $request->mesp_02,
                                       'MESP_03'         => $request->mesp_03,
                                       'MESP_04'         => $request->mesp_04,
                                       'MESP_05'         => $request->mesp_05,
                                       'MESP_06'         => $request->mesp_06,
                                       'MESP_07'         => $request->mesp_07,
                                       'MESP_08'         => $request->mesp_08,
                                       'MESP_09'         => $request->mesp_09,
                                       'MESP_10'         => $request->mesp_10,
                                       'MESP_11'         => $request->mesp_11,
                                       'MESP_12'         => $request->mesp_12,

                                       'TRIMP_01'        => $trimp_01,
                                       'TRIMP_02'        => $trimp_02,
                                       'TRIMP_03'        => $trimp_03,
                                       'TRIMP_04'        => $trimp_04,

                                       'TSEMP_01'        => $tsemp_01,
                                       'TSEMP_02'        => $tsemp_02,

                                       'TOTP_01'         => $totp_01,
                                       'TOTP_02'         => $totp_02,
 
                                       'OBS_01'          => substr(trim(strtoupper($request->obs_01)),0,3999),        
                                       'STATUS_1'        => $request->status_1,

                                       'IP_M'            => $ip,
                                       'LOGIN_M'         => $nombre,
                                       'FECHA_M2'        => date('Y/m/d'),    //date('d/m/Y')
                                       'FECHA_M'         => date('Y/m/d')    //date('d/m/Y')                                
                                       ]);
            toastr()->success('Acción o meta del Programa anual actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3011;
            $xtrx_id      =        43;    //Actualizar 
            $regbitacora = regBitacoraModel::select('PERIODO_ID',  'MES_ID', 'PROCESO_ID', 'FUNCION_ID', 
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
                    toastr()->success('Trx de actualización de acción o meta del Programa anual registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                else
                    toastr()->error('Error de actualización de acción o meta del Programa anual. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
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
                toastr()->success('Trx de actualización de acción o meta del Programa anual en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/                     
        }       /************ Actualizar *******************************************/
        return redirect()->route('verdpa',$id);
    }

    //******************* Editar 1 *****************//
    public function actionEditardPA1($id, $id2){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango');
        $arbol_id      = session()->get('arbol_id');        
        $depen_id     = session()->get('depen_id');        

        $regumedida   = regUmedidaModel::select('UMED_ID','UMED_DESC')
                        ->orderBy('UMED_ID','asc')
                        ->get();  
        $regmeses     = regMesesModel::select('MES_ID','MES_DESC')
                        ->get();                              
        $regepprog    = regEPprogramaModel::select('EPPROG_ID','EPPROG_DESC')
                        ->orderBy('EPPROG_ID','asc')
                        ->get();                         
        $regepproy    = regEPproyectoModel::select('EPPROY_ID','EPPROY_DESC')
                        ->orderBy('EPPROY_ID','asc')
                        ->get();                         
        //********* Validar rol de usuario **********************/
        if(session()->get('rango') !== '0'){                          
            $regdepen   = regdepenModel::select('DEPEN_ID', 'DEPEN_DESC')
                          ->where('DEPEN_STATUS','1')
                          ->get();                                                        
        }else{
            $regdepen   = regdepenModel::select('DEPEN_ID', 'DEPEN_DESC')
                          ->where('DEPEN_STATUS','1')
                          ->where('DEPEN_ID',$depen_id)
                          ->get();            
        }                    
        $regprogeanual  = regProgeAnualModel::select('FOLIO','PERIODO_ID','DEPEN_ID1','DEPEN_ID2','DEPEN_ID3',
                          'EPPROG_ID','EPPROY_ID','FECHA_ENTREGA','FECHA_ENTREGA2','FECHA_ENTREGA3',
                          'PERIODO_ID1','MES_ID1','DIA_ID1','MES_ID2','PROGRAMA_ID','PROGRAMA_DESC',
                          'RESPONSABLE','ELABORO','AUTORIZO','OBS_1','OBS_2','STATUS_1','STATUS_2',
                          'FECREG','FECREG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')
                          ->where(  'FOLIO'     ,$id)
                          ->orderBy('PERIODO_ID','ASC')
                          ->orderBy('FOLIO'     ,'ASC')
                          ->get();
        $regprogdanual  = regProgdAnualModel::select('FOLIO','PARTIDA','PERIODO_ID','CIPREP_ID','LGOB_COD',
                          'DEPEN_ID1','DEPEN_ID2','DEPEN_ID3','EPPROG_ID','EPPROY_ID','TACCION_ID',
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
                          'SOPORTE_ID','SOPORTE_01','SOPORTE_02','SOPORTE_03','SOPORTE_04','OBS_01','OBS_02',
                          'STATUS_1','STATUS_2','FECREG','FECREG2','IP','LOGIN','FECHA_M','FECHA_M2','IP_M','LOGIN_M')        
                          ->where(['FOLIO' => $id, 'PARTIDA' => $id2])
                          //->where('FOLIO',$id)
                          //->where('PARTIDA',$id2)
                          ->first();
        if($regprogdanual->count() <= 0){
            toastr()->error('No existen registros de acciones o metas del Programa anual.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }
        return view('sicinar.programa_anual.editardProganual1',compact('nombre','usuario','regdepen','reganios','regperiodos','regmeses','regdias','regprogeanual','regprogdanual','regumedida','regepproy','regepprog'));
    }

    public function actionActualizardPA1(progdanual1Request $request, $id, $id2){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango');
        $ip            = session()->get('ip');
        $arbol_id      = session()->get('arbol_id');   
        $depen_id     = session()->get('depen_id');             

        // **************** actualizar ******************************
        $regprogdanual = regProgdAnualModel::where(['FOLIO' => $id, 'PARTIDA' => $id2]);
        if($regprogdanual->count() <= 0)
            toastr()->error('No existe Archivo digital de Ficha de justificación.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
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
                toastr()->success('Archivo digital de Ficha de justificación actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);

                /************ Bitacora inicia *************************************/ 
                setlocale(LC_TIME, "spanish");        
                $xip          = session()->get('ip');
                $xperiodo_id  = (int)date('Y');
                $xprograma_id = 1;
                $xmes_id      = (int)date('m');
                $xproceso_id  =         3;
                $xfuncion_id  =      3011;
                $xtrx_id      =        43;    //Actualizar 
                $regbitacora = regBitacoraModel::select('PERIODO_ID',  'MES_ID', 'PROCESO_ID', 'FUNCION_ID', 
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
                        toastr()->success('Trx de Archivo digital de Ficha de justificación registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                    else
                        toastr()->error('Error de Archivo digital de Ficha de justificación. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
                }else{                   
                    //*********** Obtine el no. de veces *****************************
                    $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id,  
                                 'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 
                                 'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
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
                    toastr()->success('Trx de Archivo digital de Ficha de justificación reg. en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                }   /************ Bitacora termina *************************************/                     
            }       /************ Valida si viene vacio el arc. digital ****************/
        }           /************ Actualizar *******************************************/
        return redirect()->route('verdpa',$id);
    }

    public function actionBorrardPA($id, $id2){
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
        $depen_id     = session()->get('depen_id');             

        /************ Eliminar **************************************/
        $regprogdanual  = regProgdAnualModel::where(['FOLIO' => $id, 'PARTIDA' => $id2]);
        //              ->find('UMED_ID',$id);
        if($regprogdanual->count() <= 0)
            toastr()->error('No existe acción o meta del del Programa anual.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            $regprogdanual->delete();
            toastr()->success('Acción o meta del Programa anual eliminada.','¡Ok!',['positionClass' => 'toast-bottom-right']);

            //echo 'Ya entre a borrar registro..........';
            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3011;
            $xtrx_id      =        44;     // Baja 
            $regbitacora = regBitacoraModel::select('PERIODO_ID','MES_ID', 'PROCESO_ID','FUNCION_ID', 
                           'TRX_ID', 'FOLIO', 'NO_VECES', 'FECHA_REG', 'IP', 'LOGIN', 'FECHA_M', 'IP_M', 'LOGIN_M')
                           ->where(['PERIODO_ID' => $xperiodo_id,  'MES_ID' => $xmes_id, 
                                    'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 
                                    'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                           ->get();
            if($regbitacora->count() <= 0){              // Alta
                $nuevoregBitacora = new regBitacoraModel();              
                $nuevoregBitacora->PERIODO_ID = $xperiodo_id;    // Año de transaccion 
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
                    toastr()->success('Trx de eliminar acción o meta del Programa anual registrada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                else
                    toastr()->error('Error de eliminar Trx de acción o meta del Programa anual. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
            }else{                   
                //*********** Obtine el no. de veces *****************************
                $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id,  
                                                      'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                                      'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id,'FOLIO' => $id])
                             ->max('NO_VECES');
                $xno_veces = $xno_veces+1;                        
                //*********** Termina de obtener el no de veces *****************************         
                $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                               ->where(['PERIODO_ID' => $xperiodo_id,'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id, 
                                        'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id,'FOLIO'      => $id])
                               ->update([
                                         'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                                         'IP_M'     => $regbitacora->IP       = $ip,
                                         'LOGIN_M'  => $regbitacora->LOGIN_M  = $nombre,
                                         'FECHA_M'  => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                        ]);
                toastr()->success('Trx de eliminar acción o meta del Programa anual actualizada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/                 
        }       /************* Termina de eliminar *********************************/
        return redirect()->route('verdpa',$id);
    }    

}
