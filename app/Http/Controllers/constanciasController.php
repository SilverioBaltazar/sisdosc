<?php
//**************************************************************/
//* File:       constanciasController.php
//* Proyecto:   Sistema SRPE.V1 DGPS
//¨Función:     Clases para el modulo de solicitud de constancia
//* Autor:      Ing. Silverio Baltazar Barrientos Zarate
//* Modifico:   Ing. Silverio Baltazar Barrientos Zarate
//* Fecha act.: Septiembre 2021
//* @Derechos reservados. Gobierno del Estado de México
//*************************************************************/
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\constanciaRequest;
use App\regOscModel;
use App\regBitacoraModel;
use App\regPfiscalesModel;
use App\regSolConstanciaModel;
use App\regPerModel;
use App\regNumerosModel;
use App\regFormatosModel;
 
// Exportar a excel 
use Maatwebsite\Excel\Facades\Excel;

// Exportar a pdf
use PDF;
//use Options;

class constanciasController extends Controller
{

    //******** Buscar solicitud de constancia *****//
    public function actionBuscarConst(Request $request){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $ip           = session()->get('ip');
        $arbol_id     = session()->get('arbol_id');        

        $regperiodicidad= regPerModel::select('PER_ID','PER_DESC')->orderBy('PER_ID','asc')
                        ->get();                          
        $regnumeros   = regNumerosModel::select('NUM_ID','NUM_DESC')->get();
        $regformatos  = regFormatosModel::select('FORMATO_ID','FORMATO_DESC')->get();
        $regperiodos  = regPfiscalesModel::select('PERIODO_ID','PERIODO_DESC')->get();        
        //**************************************************************//
        // ***** busqueda https://github.com/rimorsoft/Search-simple ***//
        // ***** video https://www.youtube.com/watch?v=bmtD9GUaszw   ***//                            
        //**************************************************************//
        $fper    = $request->get('fper');   
        $idd     = $request->get('idd');  
        $nameiap = $request->get('nameiap');                  
        //********* Validar rol de usuario **********************/
        if(session()->get('rango') !== '0'){   
            $regosc       = regOscModel::select('OSC_ID', 'OSC_DESC','OSC_STATUS')
                            ->get();                                      
            $regconstancia = regSolConstanciaModel::join('PE_OSC','PE_OSC.OSC_ID','=','PE_SOLICITUD_CONSTANCIA.OSC_ID')
                            ->select( 'PE_OSC.OSC_DESC','PE_SOLICITUD_CONSTANCIA.*')
                            ->orderBy('PE_SOLICITUD_CONSTANCIA.PERIODO_ID','ASC')
                            ->orderBy('PE_SOLICITUD_CONSTANCIA.OSC_ID'    ,'ASC')                            
                            ->orderBy('PE_SOLICITUD_CONSTANCIA.OSC_FOLIO' ,'ASC')
                            ->fper($fper)           //Metodos personalizados es equvalente a ->where('OSC_DESC', 'LIKE', "%$name%");
                            ->idd($idd)             //Metodos personalizados  
                            ->nameiap($nameiap)     //Metodos personalizados                                                                                                            
                            ->paginate(30);
        }else{  
            $regosc       = regOscModel::select('OSC_ID', 'OSC_DESC','OSC_STATUS')
                            ->where('OSC_ID',$arbol_id)
                            ->get();                                  
            $regconstancia = regSolConstanciaModel::join('PE_OSC','PE_OSC.OSC_ID','=','PE_SOLICITUD_CONSTANCIA.OSC_ID')
                            ->select( 'PE_OSC.OSC_DESC','PE_SOLICITUD_CONSTANCIA.*')
                            ->where(  'PE_SOLICITUD_CONSTANCIA.OSC_ID'    ,$arbol_id)
                            ->orderBy('PE_SOLICITUD_CONSTANCIA.PERIODO_ID','ASC')
                            ->orderBy('PE_SOLICITUD_CONSTANCIA.OSC_ID'    ,'ASC')
                            ->orderBy('PE_SOLICITUD_CONSTANCIA.OSC_FOLIO' ,'ASC') 
                            ->fper($fper)           //Metodos personalizados es equvalente a ->where('OSC_DESC', 'LIKE', "%$name%");
                            ->idd($idd)             //Metodos personalizados
                            ->nameiap($nameiap)     //Metodos personalizados                                                                               
                            ->paginate(30);            
        }
        if($regconstancia->count() <= 0){
            toastr()->error('No existen solicitud de constancia.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }
        return view('sicinar.constancias.verConst',compact('nombre','usuario','regosc','regperiodicidad','regnumeros', 'regperiodos','regconstancia','regformatos'));
    }

    //******** Mostrar solicitud de constancia *****//
    public function actionVerConst(){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $ip           = session()->get('ip');
        $arbol_id     = session()->get('arbol_id');        

        $regperiodicidad= regPerModel::select('PER_ID','PER_DESC')->orderBy('PER_ID','asc')
                        ->get();                          
        $regnumeros   = regNumerosModel::select('NUM_ID','NUM_DESC')->get();
        $regformatos  = regFormatosModel::select('FORMATO_ID','FORMATO_DESC')->get();
        $regperiodos  = regPfiscalesModel::select('PERIODO_ID','PERIODO_DESC')->get();        
        //********* Validar rol de usuario **********************/
        if(session()->get('rango') !== '0'){  
            $regosc       = regOscModel::select('OSC_ID', 'OSC_DESC','OSC_STATUS')
                            ->get();                                                    
            $regconstancia  = regSolConstanciaModel::select('OSC_FOLIO','OSC_ID','PERIODO_ID',
                            'DOC_ID1' ,'FORMATO_ID1' ,'OSC_D1' ,'PER_ID1' ,'NUM_ID1' ,'OSC_EDO1',
                            'DOC_ID2' ,'FORMATO_ID2' ,'OSC_D2' ,'PER_ID2' ,'NUM_ID2' ,'OSC_EDO2',
                            'DOC_ID3' ,'FORMATO_ID3' ,'OSC_D3' ,'PER_ID3' ,'NUM_ID3' ,'OSC_EDO3',
                            'DOC_ID4' ,'FORMATO_ID4' ,'OSC_D4' ,'PER_ID4' ,'NUM_ID4' ,'OSC_EDO4',                        
                            'DOC_ID5' ,'FORMATO_ID5' ,'OSC_D5' ,'PER_ID5' ,'NUM_ID5' ,'OSC_EDO5',                    
                            'OSC_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                            ->orderBy('PERIODO_ID','ASC')
                            ->orderBy('OSC_ID'    ,'ASC')                            
                            ->orderBy('OSC_FOLIO' ,'ASC') 
                            ->paginate(30);
        }else{  
            $regosc       = regOscModel::select('OSC_ID', 'OSC_DESC','OSC_STATUS')
                            ->where('OSC_ID',$arbol_id)
                            ->get();                    
            $regconstancia  = regSolConstanciaModel::select('OSC_FOLIO','OSC_ID','PERIODO_ID',
                            'DOC_ID1' ,'FORMATO_ID1' ,'OSC_D1' ,'PER_ID1' ,'NUM_ID1' ,'OSC_EDO1',
                            'DOC_ID2' ,'FORMATO_ID2' ,'OSC_D2' ,'PER_ID2' ,'NUM_ID2' ,'OSC_EDO2',
                            'DOC_ID3' ,'FORMATO_ID3' ,'OSC_D3' ,'PER_ID3' ,'NUM_ID3' ,'OSC_EDO3',
                            'DOC_ID4' ,'FORMATO_ID4' ,'OSC_D4' ,'PER_ID4' ,'NUM_ID4' ,'OSC_EDO4',                        
                            'DOC_ID5' ,'FORMATO_ID5' ,'OSC_D5' ,'PER_ID5' ,'NUM_ID5' ,'OSC_EDO5',                
                            'OSC_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                            ->where(  'OSC_ID'    ,$arbol_id)
                            ->orderBy('PERIODO_ID','ASC')
                            ->orderBy('OSC_ID'    ,'ASC')                            
                            ->orderBy('OSC_FOLIO' ,'ASC')                             
                            ->paginate(30);            
        }
        if($regconstancia->count() <= 0){
            toastr()->error('No existen solicitud de constancia.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevooperativo');
        }
        return view('sicinar.constancias.verConst',compact('nombre','usuario','regosc','regperiodicidad','regnumeros', 'regperiodos','regconstancia','regformatos'));
    }


    public function actionNuevaConst(){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $ip           = session()->get('ip');
        $arbol_id     = session()->get('arbol_id');        

        $regnumeros   = regNumerosModel::select('NUM_ID', 'NUM_DESC')->orderBy('NUM_ID','asc')
                        ->get();
        $regperiodos  = regPfiscalesModel::select('PERIODO_ID', 'PERIODO_DESC')->orderBy('PERIODO_ID','asc')
                        ->get();        
        $regperiodicidad= regPerModel::select('PER_ID', 'PER_DESC')->orderBy('PER_ID','asc')
                        ->get();   
        $regformatos  = regFormatosModel::select('FORMATO_ID','FORMATO_DESC')->get();
        if(session()->get('rango') !== '0'){        
            $regosc   = regOscModel::select('OSC_ID', 'OSC_DESC','OSC_STATUS')->orderBy('OSC_DESC','asc')
                        ->get();
        }else{
            $regosc   = regOscModel::select('OSC_ID', 'OSC_DESC','OSC_STATUS')
                        ->where('OSC_ID',$arbol_id)
                        ->get();            
        }                        
        $regconstancia  = regSolConstanciaModel::select('OSC_FOLIO','OSC_ID','PERIODO_ID',
                        'DOC_ID1' ,'FORMATO_ID1' ,'OSC_D1' ,'PER_ID1' ,'NUM_ID1' ,'OSC_EDO1',
                        'DOC_ID2' ,'FORMATO_ID2' ,'OSC_D2' ,'PER_ID2' ,'NUM_ID2' ,'OSC_EDO2',
                        'DOC_ID3' ,'FORMATO_ID3' ,'OSC_D3' ,'PER_ID3' ,'NUM_ID3' ,'OSC_EDO3',
                        'DOC_ID4' ,'FORMATO_ID4' ,'OSC_D4' ,'PER_ID4' ,'NUM_ID4' ,'OSC_EDO4',                        
                        'DOC_ID5' ,'FORMATO_ID5' ,'OSC_D5' ,'PER_ID5' ,'NUM_ID5' ,'OSC_EDO5',            
                        'OSC_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->orderBy('OSC_ID','ASC')
                        ->get();        
        //dd($unidades);
        return view('sicinar.constancias.nuevaConst',compact('regper','regnumeros','regosc','regperiodos','regperiodicidad','nombre','usuario','regconstancia','regformatos'));
    }

    public function actionAltaNuevaConst(Request $request){
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
 
        /************ Registro *****************************/ 
        $regconstancia  = regSolConstanciaModel::select('OSC_FOLIO','OSC_ID','PERIODO_ID',
                        'DOC_ID1' ,'FORMATO_ID1' ,'OSC_D1' ,'PER_ID1' ,'NUM_ID1' ,'OSC_EDO1',
                        'DOC_ID2' ,'FORMATO_ID2' ,'OSC_D2' ,'PER_ID2' ,'NUM_ID2' ,'OSC_EDO2',
                        'DOC_ID3' ,'FORMATO_ID3' ,'OSC_D3' ,'PER_ID3' ,'NUM_ID3' ,'OSC_EDO3',
                        'DOC_ID4' ,'FORMATO_ID4' ,'OSC_D4' ,'PER_ID4' ,'NUM_ID4' ,'OSC_EDO4',                        
                        'DOC_ID5' ,'FORMATO_ID5' ,'OSC_D5' ,'PER_ID5' ,'NUM_ID5' ,'OSC_EDO5',             
                        'OSC_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where(['PERIODO_ID' => $request->periodo_id,'OSC_ID' => $request->osc_id])
                        ->get();
        if($regconstancia->count() <= 0 && !empty($request->osc_id)){
            //********** Registrar la alta *****************************/
            $osc_folio = regSolConstanciaModel::max('OSC_FOLIO');
            $osc_folio = $osc_folio+1;
            $nuevooperativo = new regSolConstanciaModel();

            $file1 =null;
            if(isset($request->osc_d1)){
                if(!empty($request->osc_d1)){
                    //Comprobar  si el campo act_const tiene un archivo asignado:
                    if($request->hasFile('osc_d1')){
                        $file1=$request->osc_id.'_'.$request->file('osc_d1')->getClientOriginalName();
                        //sube el archivo a la carpeta del servidor public/images/
                        $request->file('osc_d1')->move(public_path().'/images/', $file1);
                    }
                }
            }            
            $file2 =null;
            if(isset($request->osc_d2)){
                if(!empty($request->osc_d2)){
                    //Comprobar  si el campo act_const tiene un archivo asignado:
                    if($request->hasFile('osc_d2')){
                        $file2=$request->osc_id.'_'.$request->file('osc_d2')->getClientOriginalName();
                        //sube el archivo a la carpeta del servidor public/images/
                        $request->file('osc_d2')->move(public_path().'/images/', $file2);
                    }
                }
            }

            $nuevooperativo->OSC_FOLIO    = $osc_folio;
            $nuevooperativo->PERIODO_ID   = $request->periodo_id;
            $nuevooperativo->OSC_ID       = $request->osc_id;        

            $nuevooperativo->DOC_ID1      = $request->doc_id1;
            $nuevooperativo->FORMATO_ID1  = $request->formato_id1;
            $nuevooperativo->OSC_D1       = $file1;        
            $nuevooperativo->NUM_ID1      = $request->num_id1;
            $nuevooperativo->PER_ID1      = $request->per_id1;        
            $nuevooperativo->OSC_EDO1     = $request->osc_edo1;

            //$nuevooperativo->DOC_ID2      = $request->doc_id2;
            //$nuevooperativo->FORMATO_ID2  = $request->formato_id2;
            //$nuevooperativo->OSC_D2       = $file2;        
            //$nuevooperativo->NUM_ID2      = 1;   // 1     $request->num_id2;
            //$nuevooperativo->PER_ID2      = 5;   // Anual $request->per_id2;        
            //$nuevooperativo->OSC_EDO2   = $request->osc_edo2;

            $nuevooperativo->IP           = $ip;
            $nuevooperativo->LOGIN        = $nombre;         // Usuario ;
            $nuevooperativo->save();

            if($nuevooperativo->save() == true){
                toastr()->success('solicitud de constancia registrada.','ok!',['positionClass' => 'toast-bottom-right']);

                /************ Bitacora inicia *************************************/ 
                setlocale(LC_TIME, "spanish");        
                $xip          = session()->get('ip');
                $xperiodo_id  = (int)date('Y');
                $xprograma_id = 1;
                $xmes_id      = (int)date('m');
                $xproceso_id  =         3;
                $xfuncion_id  =      3012;
                $xtrx_id      =        89;    //alta
                $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID', 'MES_ID', 'PROCESO_ID', 
                                           'FUNCION_ID','TRX_ID','FOLIO','NO_VECES','FECHA_REG','IP','LOGIN',
                                           'FECHA_M', 'IP_M', 'LOGIN_M')
                               ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 
                                        'MES_ID' => $xmes_id,'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 
                                        'TRX_ID' => $xtrx_id, 'FOLIO' => $osc_folio])
                               ->get();
                if($regbitacora->count() <= 0){              // Alta
                    $nuevoregBitacora = new regBitacoraModel();              
                    $nuevoregBitacora->PERIODO_ID = $xperiodo_id;    // Año de transaccion 
                    $nuevoregBitacora->PROGRAMA_ID= $xprograma_id;   // Proyecto JAPEM 
                    $nuevoregBitacora->MES_ID     = $xmes_id;        // Mes de transaccion
                    $nuevoregBitacora->PROCESO_ID = $xproceso_id;    // Proceso de apoyo
                    $nuevoregBitacora->FUNCION_ID = $xfuncion_id;    // Funcion del modelado de procesos 
                    $nuevoregBitacora->TRX_ID     = $xtrx_id;        // Actividad del modelado de procesos
                    $nuevoregBitacora->FOLIO      = $osc_folio;      // Folio    
                    $nuevoregBitacora->NO_VECES   = 1;               // Numero de veces            
                    $nuevoregBitacora->IP         = $ip;             // IP
                    $nuevoregBitacora->LOGIN      = $nombre;         // Usuario 
                    $nuevoregBitacora->save();
                    if($nuevoregBitacora->save() == true)
                       toastr()->success('Trx solicitud de constancia dada de alta en bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                    else
                       toastr()->error('Error trx solicitud de constancia al dar de alta en bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
                }else{                   
                    //*********** Obtine el no. de veces *****************************
                    $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 
                                                          'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                                          'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 
                                                          'FOLIO' => $osc_folio])
                                 ->max('NO_VECES');
                    $xno_veces = $xno_veces+1;                        
                    //*********** Termina de obtener el no de veces *****************************               
                    $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                                   ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 
                                            'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                            'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id,
                                            'FOLIO' => $osc_folio])
                                   ->update([
                                             'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                                             'IP_M'     => $regbitacora->IP       = $ip,
                                             'LOGIN_M'  => $regbitacora->LOGIN_M  = $nombre,
                                             'FECHA_M'  => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                            ]);
                    toastr()->success('Trx de solicitud de constancia actualizada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                }   /************ Bitacora termina *************************************/ 
                
                //return redirect()->route('nuevooperativo');
                //return view('sicinar.plandetrabajo.nuevoPlan',compact('unidades','nombre','usuario','rango','preguntas','apartados'));
            }else{
                toastr()->error('Error en trx de solicitud de constancia en bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
            }   //******************** Termina la alta ************************/ 

        }else{
            toastr()->error('Ya existen solicitud de constancia.','Por favor editar, lo siento!',['positionClass' => 'toast-bottom-right']);
        }   // Termian If de busqueda ****************

        return redirect()->route('verConst');
    }


    /****************** Editar registro  **********/
    public function actionEditarConst($id){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango');
        $arbol_id      = session()->get('arbol_id');        

        $regnumeros   = regNumerosModel::select('NUM_ID', 'NUM_DESC')->orderBy('NUM_ID','asc')
                        ->get();
        $regperiodos  = regPfiscalesModel::select('PERIODO_ID', 'PERIODO_DESC')->orderBy('PERIODO_ID','asc')
                        ->get();        
        $regperiodicidad= regPerModel::select('PER_ID', 'PER_DESC')->orderBy('PER_ID','asc')
                        ->get();                                
        $regformatos  = regFormatosModel::select('FORMATO_ID','FORMATO_DESC')->get();
        $regosc       = regOscModel::select('OSC_ID', 'OSC_DESC','OSC_STATUS')->orderBy('OSC_DESC','asc')
                        ->get();
        $regconstancia  = regSolConstanciaModel::select('OSC_FOLIO','OSC_ID','PERIODO_ID',
                        'DOC_ID1' ,'FORMATO_ID1' ,'OSC_D1' ,'PER_ID1' ,'NUM_ID1' ,'OSC_EDO1',
                        'DOC_ID2' ,'FORMATO_ID2' ,'OSC_D2' ,'PER_ID2' ,'NUM_ID2' ,'OSC_EDO2',
                        'DOC_ID3' ,'FORMATO_ID3' ,'OSC_D3' ,'PER_ID3' ,'NUM_ID3' ,'OSC_EDO3',
                        'DOC_ID4' ,'FORMATO_ID4' ,'OSC_D4' ,'PER_ID4' ,'NUM_ID4' ,'OSC_EDO4',                        
                        'DOC_ID5' ,'FORMATO_ID5' ,'OSC_D5' ,'PER_ID5' ,'NUM_ID5' ,'OSC_EDO5',             
                        'OSC_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where('OSC_FOLIO', $id)
                        ->first();
        if($regconstancia->count() <= 0){
            toastr()->error('No existen solicitud de constancia.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }
        return view('sicinar.constancias.editarConst',compact('nombre','usuario','regosc','regconstancia','regnumeros', 'regperiodos','regperiodicidad','regformatos'));
    }
    
    public function actionActualizarConst(constanciaRequest $request, $id){
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
        $regconstancia = regSolConstanciaModel::where('OSC_FOLIO',$id);
        if($regconstancia->count() <= 0)
            toastr()->error('No existe solicitud de constancia.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            //****************** Actualizar **************************/
            //echo "Escribió en el campo de texto 1: " .'-'. $request->osc_d9 .'-'. "<br><br>"; 
            //echo "Escribió en el campo de texto 1: " . $request->osc_d9 . "<br><br>"; 
            //Comprobar  si el campo foto1 tiene un archivo asignado:
            $name1 =null;        
            if($request->hasFile('osc_d1')){
                $name1 = $id.'_'.$request->file('osc_d1')->getClientOriginalName(); 
                //sube el archivo a la carpeta del servidor public/images/
                $request->file('osc_d1')->move(public_path().'/images/', $name1);
            }            
            $name2 =null;        
            if($request->hasFile('osc_d2')){
                $name2 = $id.'_'.$request->file('osc_d2')->getClientOriginalName(); 
                //sube el archivo a la carpeta del servidor public/images/
                $request->file('osc_d2')->move(public_path().'/images/', $name2);
            }            

            // ************* Actualizamos registro **********************************/
            $regconstancia = regSolConstanciaModel::where('OSC_FOLIO',$id)        
                           ->update([                
                                     //'PERIODO_ID'   => $request->periodo_id,

                                     'DOC_ID1'      => $request->doc_id1,
                                     'FORMATO_ID1'  => $request->formato_id1,                            
                                     //'OSC_D1'     => $name1,                                                       
                                     //'PER_ID1'    => $request->per_id1,
                                     //'NUM_ID1'    => $request->num_id1,                
                                     //'OSC_EDO1'   => $request->osc_edo1,

                                     //'DOC_ID2'      => $request->doc_id2,
                                     //'FORMATO_ID2'  => $request->formato_id2,                            
                                     //'OSC_D2'     => $name2,                                                       
                                     //'PER_ID2'    => $request->per_id2,
                                     //'NUM_ID2'    => $request->num_id2,                
                                     //'OSC_EDO2'   => $request->osc_edo2,

                                     //'OSC_STATUS'   => $request->osc_status,
                                     'IP_M'         => $ip,
                                     'LOGIN_M'      => $nombre,
                                     'FECHA_M'      => date('Y/m/d')    //date('d/m/
                                    ]);
            toastr()->success('solicitud de constancia actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3012;
            $xtrx_id      =        90;    //Actualizar         
            $regbitacora = regBitacoraModel::select('PERIODO_ID','PROGRAMA_ID','MES_ID','PROCESO_ID','FUNCION_ID', 
                                                    'TRX_ID','FOLIO','NO_VECES','FECHA_REG','IP','LOGIN','FECHA_M',
                                                    'IP_M','LOGIN_M')
                           ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 
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
                    toastr()->success('Trx de solicitud de constancia dada de alta en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                else
                    toastr()->error('Error de trxt de solicitud de constancia al dar de alta en bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
            }else{                   
                //*********** Obtine el no. de veces *****************************
                $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                        ->max('NO_VECES');
                $xno_veces = $xno_veces+1;                        
                //*********** Termina de obtener el no de veces *****************************                    
                $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                               ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id,
                                        'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 
                                        'FOLIO' => $id])
                               ->update([
                                          'NO_VECES'=> $regbitacora->NO_VECES = $xno_veces,
                                          'IP_M'    => $regbitacora->IP       = $ip,
                                          'LOGIN_M' => $regbitacora->LOGIN_M  = $nombre,
                                          'FECHA_M' => $regbitacora->FECHA_M  = date('Y/m/d')  //date('d/m/Y')
                                        ]);
                toastr()->success('Trx de solicitud de constancia actualizada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/         
        }       /************ Termina actualizar ***********************************/
        return redirect()->route('verConst');
    }

    /****************** Editar Solicitud de constancia **********/
    public function actionEditarConst1($id){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango');
        $arbol_id      = session()->get('arbol_id');        

        $regnumeros   = regNumerosModel::select('NUM_ID', 'NUM_DESC')->orderBy('NUM_ID','asc')
                        ->get();
        $regperiodos  = regPfiscalesModel::select('PERIODO_ID', 'PERIODO_DESC')->orderBy('PERIODO_ID','asc')
                        ->get();        
        $regperiodicidad= regPerModel::select('PER_ID', 'PER_DESC')->orderBy('PER_ID','asc')
                        ->get(); 
        $regformatos  = regFormatosModel::select('FORMATO_ID','FORMATO_DESC')->get();                               
        $regosc       = regOscModel::select('OSC_ID', 'OSC_DESC','OSC_STATUS')->orderBy('OSC_DESC','asc')
                        ->get();
        $regconstancia  = regSolConstanciaModel::select('OSC_FOLIO','OSC_ID','PERIODO_ID',
                        'DOC_ID1' ,'FORMATO_ID1' ,'OSC_D1' ,'PER_ID1' ,'NUM_ID1' ,'OSC_EDO1',
                        'DOC_ID2' ,'FORMATO_ID2' ,'OSC_D2' ,'PER_ID2' ,'NUM_ID2' ,'OSC_EDO2',
                        'DOC_ID3' ,'FORMATO_ID3' ,'OSC_D3' ,'PER_ID3' ,'NUM_ID3' ,'OSC_EDO3',
                        'DOC_ID4' ,'FORMATO_ID4' ,'OSC_D4' ,'PER_ID4' ,'NUM_ID4' ,'OSC_EDO4',                        
                        'DOC_ID5' ,'FORMATO_ID5' ,'OSC_D5' ,'PER_ID5' ,'NUM_ID5' ,'OSC_EDO5',             
                        'OSC_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where('OSC_FOLIO', $id)
                        ->first();
        if($regconstancia->count() <= 0){
            toastr()->error('No existe solicitud de constancia.','Lo siento!',['positionClass' => 'toast-bottom-right']);
        }
        return view('sicinar.constancias.editarConst1',compact('nombre','usuario','regosc','regconstancia','regnumeros', 'regperiodos','regperiodicidad','regformatos'));
    }

    public function actionActualizarConst1(constanciaRequest $request, $id){
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
        $regconstancia = regSolConstanciaModel::where('OSC_FOLIO', $id);
        if($regconstancia->count() <= 0)
            toastr()->error('No existe archivo PDF 1.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        

            //echo "Escribió en el campo de texto 1: " .'-'. $request->osc_d9 .'-'. "<br><br>"; 
            //echo "Escribió en el campo de texto 1: " . $request->osc_d9 . "<br><br>"; 
            //Comprobar  si el campo foto1 tiene un archivo asignado:
            $name1 =null;
            if($request->hasFile('osc_d1')){
                echo "Escribió en el campo de texto 1: " .'-'. $request->osc_d1 .'-'. "<br><br>"; 
                $name1 = $id.'_'.$request->file('osc_d1')->getClientOriginalName(); 
                //sube el archivo a la carpeta del servidor public/images/
                $request->file('osc_d1')->move(public_path().'/images/', $name1);

                // ************* Actualizamos registro **********************************
                $regconstancia = regSolConstanciaModel::where('OSC_FOLIO',$id)        
                           ->update([   
                                     'DOC_ID1'    => $request->doc_id1,
                                     'FORMATO_ID1'=> $request->formato_id1,             
                                     'OSC_D1'     => $name1,                  
                                     //'PER_ID1'  => $request->per_id1,
                                     //'NUM_ID1'  => $request->num_id1,                
                                     //'OSC_EDO1' => $request->osc_edo1,

                                     'IP_M'       => $ip,
                                     'LOGIN_M'    => $nombre,
                                     'FECHA_M'    => date('Y/m/d')    //date('d/m/Y')                                
                                     ]);
                toastr()->success('archivo operativo 1 actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }else{
                // ************* Actualizamos registro **********************************
                $regconstancia = regSolConstanciaModel::where('OSC_FOLIO',$id)        
                           ->update([   
                                     'DOC_ID1'    => $request->doc_id1,
                                     'FORMATO_ID1'=> $request->formato_id1,             
                                     //'OSC_D1'   => $name1,                  
                                     //'PER_ID1'  => $request->per_id1,
                                     //'NUM_ID1'  => $request->num_id1,                
                                     //'OSC_EDO1' => $request->osc_edo1,

                                     'IP_M'       => $ip,
                                     'LOGIN_M'    => $nombre,
                                     'FECHA_M'    => date('Y/m/d')    //date('d/m/Y')                                
                                     ]);                
                toastr()->success('archivo operativo 1 actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3012;
            $xtrx_id      =        90;    //Actualizar         
            $regbitacora = regBitacoraModel::select('PERIODO_ID','PROGRAMA_ID','MES_ID','PROCESO_ID','FUNCION_ID', 
                               'TRX_ID','FOLIO','NO_VECES','FECHA_REG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                           ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 
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
                    $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 
                                                      'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                                      'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                                 ->max('NO_VECES');
                    $xno_veces = $xno_veces+1;                        
                    //*********** Termina de obtener el no de veces *****************************                    
                    $regbitacora= regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                                  ->where(['PERIODO_ID' => $xperiodo_id,'PROGRAMA_ID' => $xprograma_id,'MES_ID' => $xmes_id,
                                           'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id, 
                                           'FOLIO' => $id])
                                  ->update([
                                            'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                                            'IP_M' => $regbitacora->IP           = $ip,
                                            'LOGIN_M' => $regbitacora->LOGIN_M   = $nombre,
                                            'FECHA_M' => $regbitacora->FECHA_M   = date('Y/m/d')  //date('d/m/Y')
                                          ]);
                    toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/         
        }       /************ Termina de actualizar ********************************/
        return redirect()->route('verConst');
    }    

    /****************** Editar solicitud de constancia **********/
    public function actionEditarConst2($id){
        $nombre        = session()->get('userlog');
        $pass          = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario       = session()->get('usuario');
        $rango         = session()->get('rango');
        $arbol_id      = session()->get('arbol_id');        

        $regnumeros   = regNumerosModel::select('NUM_ID', 'NUM_DESC')->orderBy('NUM_ID','asc')
                        ->get();
        $regperiodos  = regPfiscalesModel::select('PERIODO_ID', 'PERIODO_DESC')->orderBy('PERIODO_ID','asc')
                        ->get();        
        $regperiodicidad= regPerModel::select('PER_ID', 'PER_DESC')->orderBy('PER_ID','asc')
                        ->get(); 
        $regformatos  = regFormatosModel::select('FORMATO_ID','FORMATO_DESC')->get();                               
        $regosc       = regOscModel::select('OSC_ID', 'OSC_DESC','OSC_STATUS')->orderBy('OSC_DESC','asc')
                        ->get();
        $regconstancia  = regSolConstanciaModel::select('OSC_FOLIO','OSC_ID','PERIODO_ID',
                        'DOC_ID1' ,'FORMATO_ID1' ,'OSC_D1' ,'PER_ID1' ,'NUM_ID1' ,'OSC_EDO1',
                        'DOC_ID2' ,'FORMATO_ID2' ,'OSC_D2' ,'PER_ID2' ,'NUM_ID2' ,'OSC_EDO2',
                        'DOC_ID3' ,'FORMATO_ID3' ,'OSC_D3' ,'PER_ID3' ,'NUM_ID3' ,'OSC_EDO3',
                        'DOC_ID4' ,'FORMATO_ID4' ,'OSC_D4' ,'PER_ID4' ,'NUM_ID4' ,'OSC_EDO4',                        
                        'DOC_ID5' ,'FORMATO_ID5' ,'OSC_D5' ,'PER_ID5' ,'NUM_ID5' ,'OSC_EDO5',             
                        'OSC_STATUS','FECREG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                        ->where('OSC_FOLIO', $id)
                        ->first();
        if($regconstancia->count() <= 0){
            toastr()->error('No existe requisito operativo 2.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevooperativo');
        }
        return view('sicinar.constancias.editarConst2',compact('nombre','usuario','regosc','regconstancia','regnumeros', 'regperiodos','regperiodicidad','regformatos'));
    }

    public function actionActualizarConst2(constanciaRequest $request, $id){
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
        $regconstancia = regSolConstanciaModel::where('OSC_FOLIO',$id);
        if($regconstancia->count() <= 0)
            toastr()->error('No existe archivo PDF 2.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        

            //echo "Escribió en el campo de texto 1: " .'-'. $request->osc_d9 .'-'. "<br><br>"; 
            //echo "Escribió en el campo de texto 1: " . $request->osc_d9 . "<br><br>"; 
            //Comprobar  si el campo foto1 tiene un archivo asignado:
            $preg_003  =str_replace(",", "", $request->preg_003);
            $preg_004  =str_replace(",", "", $request->preg_004);
            $preg_005  =str_replace(",", "", $request->preg_005); 

            $name2 =null;
            if($request->hasFile('osc_d2')){
                echo "Escribió en el campo de texto 2: " .'-'. $request->osc_d2 .'-'. "<br><br>"; 
                $name2 = $id.'_'.$request->file('osc_d2')->getClientOriginalName(); 
                //sube el archivo a la carpeta del servidor public/images/
                $request->file('osc_d2')->move(public_path().'/images/', $name2);

                // ************* Actualizamos registro **********************************
                $regconstancia = regSolConstanciaModel::where('OSC_FOLIO',$id)        
                           ->update([   
                                     'DOC_ID2'    => $request->doc_id2,
                                     'FORMATO_ID2'=> $request->formato_id2,             
                                     'OSC_D2'     => $name2,                  
                                     //'PER_ID2'  => $request->per_id2,
                                     //'NUM_ID2'  => $request->num_id2,                
                                     //'OSC_EDO2' => $request->osc_edo2,

                                     'IP_M'       => $ip,
                                     'LOGIN_M'    => $nombre,
                                     'FECHA_M'    => date('Y/m/d')    //date('d/m/Y')                                
                                     ]);
                toastr()->success('archivo 2 PDF actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }else{
                // ************* Actualizamos registro **********************************
                $regconstancia = regSolConstanciaModel::where('OSC_FOLIO',$id)        
                           ->update([   
                                     'DOC_ID2'    => $request->doc_id2,
                                     'FORMATO_ID2'=> $request->formato_id2,             
                                     //'OSC_D2'   => $name2,                  
                                     //'PER_ID2'  => $request->per_id2,
                                     //'NUM_ID2'  => $request->num_id2,                
                                     //'OSC_EDO2' => $request->osc_edo2,

                                     'IP_M'        => $ip,
                                     'LOGIN_M'     => $nombre,
                                     'FECHA_M'     => date('Y/m/d')    //date('d/m/Y')                                
                                     ]);                
                toastr()->success('archivo 2 PDF actualizado.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }

            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3012;
            $xtrx_id      =        90;    //Actualizar         
            $regbitacora = regBitacoraModel::select('PERIODO_ID','PROGRAMA_ID','MES_ID','PROCESO_ID','FUNCION_ID', 
                               'TRX_ID','FOLIO','NO_VECES','FECHA_REG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                           ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 
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
                    $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 
                                                      'MES_ID' => $xmes_id, 'PROCESO_ID' => $xproceso_id, 
                                                      'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                                 ->max('NO_VECES');
                    $xno_veces = $xno_veces+1;                        
                    //*********** Termina de obtener el no de veces *****************************                    
                    $regbitacora= regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                                  ->where(['PERIODO_ID' => $xperiodo_id,'PROGRAMA_ID' => $xprograma_id,'MES_ID' => $xmes_id,
                                           'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id,'TRX_ID' => $xtrx_id, 
                                           'FOLIO' => $id])
                                  ->update([
                                            'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                                            'IP_M' => $regbitacora->IP           = $ip,
                                            'LOGIN_M' => $regbitacora->LOGIN_M   = $nombre,
                                            'FECHA_M' => $regbitacora->FECHA_M   = date('Y/m/d')  //date('d/m/Y')
                                          ]);
                    toastr()->success('Bitacora actualizada.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/         
        }       /************ Termina de actualizar ********************************/
        return redirect()->route('verConst');
    }    

     
    //***** Borrar registro completo ***********************
    public function actionBorrarConst($id){
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

        /************ Elimina transacción de asistencia social y operativo ***************/
        $regconstancia = regSolConstanciaModel::where('OSC_FOLIO',$id);
        if($regconstancia->count() <= 0)
            toastr()->error('No existe solicitud de constancia.','¡Por favor volver a intentar!',['positionClass' => 'toast-bottom-right']);
        else{        
            $regconstancia->delete();
            toastr()->success('Solicitud de constancia eliminado.','¡Ok!',['positionClass' => 'toast-bottom-right']);

            //echo 'Ya entre aboorar registro..........';
            /************ Bitacora inicia *************************************/ 
            setlocale(LC_TIME, "spanish");        
            $xip          = session()->get('ip');
            $xperiodo_id  = (int)date('Y');
            $xprograma_id = 1;
            $xmes_id      = (int)date('m');
            $xproceso_id  =         3;
            $xfuncion_id  =      3012;
            $xtrx_id      =        91;     // borrar 
            $regbitacora = regBitacoraModel::select('PERIODO_ID', 'PROGRAMA_ID','MES_ID','PROCESO_ID','FUNCION_ID', 
                           'TRX_ID','FOLIO','NO_VECES','FECHA_REG','IP','LOGIN','FECHA_M','IP_M','LOGIN_M')
                           ->where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 'MES_ID' => $xmes_id, 
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
                    toastr()->success('Trx de solicitud de constancia dada de alta en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
                else
                    toastr()->error('Error inesperado en trx de solicitud de constancia bitacora. Por favor volver a interlo.','Ups!',['positionClass' => 'toast-bottom-right']);
            }else{                   
                //*********** Obtine el no. de veces *****************************
                $xno_veces = regBitacoraModel::where(['PERIODO_ID' => $xperiodo_id, 'PROGRAMA_ID' => $xprograma_id, 
                             'MES_ID'  => $xmes_id, 'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 
                             'TRX_ID' => $xtrx_id, 'FOLIO' => $id])
                             ->max('NO_VECES');
                $xno_veces = $xno_veces+1;                        
                //*********** Termina de obtener el no de veces *****************************         
                $regbitacora = regBitacoraModel::select('NO_VECES','IP_M','LOGIN_M','FECHA_M')
                               ->where(['PERIODO_ID' => $xperiodo_id,'PROGRAMA_ID' => $xprograma_id,'MES_ID' => $xmes_id, 
                                        'PROCESO_ID' => $xproceso_id, 'FUNCION_ID' => $xfuncion_id, 'TRX_ID' => $xtrx_id, 
                                        'FOLIO' => $id])
                               ->update([
                                         'NO_VECES' => $regbitacora->NO_VECES = $xno_veces,
                                         'IP_M' => $regbitacora->IP           = $ip,
                                         'LOGIN_M' => $regbitacora->LOGIN_M   = $nombre,
                                         'FECHA_M' => $regbitacora->FECHA_M   = date('Y/m/d')  //date('d/m/Y')
                                        ]);
                toastr()->success('Trx de solicitud de constancia actualizada en Bitacora.','¡Ok!',['positionClass' => 'toast-bottom-right']);
            }   /************ Bitacora termina *************************************/    
        }       /************* Termina de eliminar  *********************************/
        return redirect()->route('verConst');
    }    

}
