<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/  
 
Route::get('/', function () {
    return view('sicinar.login.loginInicio');
});

    Route::group(['prefix' => 'control-interno'], function() {
    Route::post('menu', 'usuariosController@actionLogin')->name('login');
    Route::get('status-sesion/expirada', 'usuariosController@actionExpirada')->name('expirada');
    Route::get('status-sesion/terminada','usuariosController@actionCerrarSesion')->name('terminada');
 
    // Auto registro en sistema
    Route::get( 'Autoregistro/usuario'              ,'usuariosController@actionAutoregUsu')->name('autoregusu');
    Route::post('Autoregistro/usuario/registro'     ,'usuariosController@actionRegaltaUsu')->name('regaltausu');
    Route::get( 'Autoregistro/{id}/editarbienvenida','usuariosController@actioneditarBienve')->name('editarbienve');        

    // BackOffice del sistema
    Route::get('BackOffice/usuarios'                ,'usuariosController@actionNuevoUsuario')->name('nuevoUsuario');
    Route::post('BackOffice/usuarios/alta'          ,'usuariosController@actionAltaUsuario')->name('altaUsuario');
    Route::get('BackOffice/buscar/todos'            ,'usuariosController@actionBuscarUsuario')->name('buscarUsuario');        
    Route::get('BackOffice/usuarios/todos'          ,'usuariosController@actionVerUsuario')->name('verUsuarios');
    Route::get('BackOffice/usuarios/{id}/editar'    ,'usuariosController@actionEditarUsuario')->name('editarUsuario');
    Route::put('BackOffice/usuarios/{id}/actualizar','usuariosController@actionActualizarUsuario')->name('actualizarUsuario');
    Route::get('BackOffice/usuarios/{id}/Borrar'    ,'usuariosController@actionBorrarUsuario')->name('borrarUsuario');    
    Route::get('BackOffice/usuario/{id}/activar'    ,'usuariosController@actionActivarUsuario')->name('activarUsuario');
    Route::get('BackOffice/usuario/{id}/desactivar' ,'usuariosController@actionDesactivarUsuario')->name('desactivarUsuario');

    Route::get('BackOffice/usuario/{id}/{id2}/email','usuariosController@actionEmailRegistro')->name('emailregistro');    

    //Catalogos
    //Procesos
    Route::get('proceso/nuevo'      ,'catalogosController@actionNuevoProceso')->name('nuevoProceso');
    Route::post('proceso/nuevo/alta','catalogosController@actionAltaNuevoProceso')->name('AltaNuevoProceso');
    Route::get('proceso/ver/todos'  ,'catalogosController@actionVerProceso')->name('verProceso');
    Route::get('proceso/{id}/editar/proceso','catalogosController@actionEditarProceso')->name('editarProceso');
    Route::put('proceso/{id}/actualizar'    ,'catalogosController@actionActualizarProceso')->name('actualizarProceso');
    Route::get('proceso/{id}/Borrar','catalogosController@actionBorrarProceso')->name('borrarProceso');    
    Route::get('proceso/excel'      ,'catalogosController@exportCatProcesosExcel')->name('downloadprocesos');
    Route::get('proceso/pdf'        ,'catalogosController@exportCatProcesosPdf')->name('catprocesosPDF');
    //Funciones de procesos
    Route::get('funcion/nuevo'      ,'catalogosfuncionesController@actionNuevaFuncion')->name('nuevaFuncion');
    Route::post('funcion/nuevo/alta','catalogosfuncionesController@actionAltaNuevaFuncion')->name('AltaNuevaFuncion');
    Route::get('funcion/ver/todos'  ,'catalogosfuncionesController@actionVerFuncion')->name('verFuncion');
    Route::get('funcion/{id}/editar/funcion','catalogosfuncionesController@actionEditarFuncion')->name('editarFuncion');
    Route::put('funcion/{id}/actualizar'    ,'catalogosfuncionesController@actionActualizarFuncion')->name('actualizarFuncion');
    Route::get('funcion/{id}/Borrar','catalogosfuncionesController@actionBorrarFuncion')->name('borrarFuncion');    
    Route::get('funcion/excel'      ,'catalogosfuncionesController@exportCatFuncionesExcel')->name('downloadfunciones');
    Route::get('funcion/pdf'        ,'catalogosfuncionesController@exportCatFuncionesPdf')->name('catfuncionesPDF');    
    //Actividades
    Route::get('actividad/nuevo'      ,'catalogostrxController@actionNuevaTrx')->name('nuevaTrx');
    Route::post('actividad/nuevo/alta','catalogostrxController@actionAltaNuevaTrx')->name('AltaNuevaTrx');
    Route::get('actividad/ver/todos'  ,'catalogostrxController@actionVerTrx')->name('verTrx');
    Route::get('actividad/{id}/editar/actividad','catalogostrxController@actionEditarTrx')->name('editarTrx');
    Route::put('actividad/{id}/actualizar'      ,'catalogostrxController@actionActualizarTrx')->name('actualizarTrx');
    Route::get('actividad/{id}/Borrar','catalogostrxController@actionBorrarTrx')->name('borrarTrx');    
    Route::get('actividad/excel'      ,'catalogostrxController@exportCatTrxExcel')->name('downloadtrx');
    Route::get('actividad/pdf'        ,'catalogostrxController@exportCatTrxPdf')->name('cattrxPDF');
    //Unidades de medida
    Route::get('umedida/nueva'      ,'catumedidaController@actionNuevaUmedida')->name('nuevaumedida');
    Route::post('umedida/alta'      ,'catumedidaController@actionAltaNuevaUmedida')->name('altanuevaumedida');
    Route::get('umedida/ver/todos'  ,'catumedidaController@actionVerUmedida')->name('verumedida');
    Route::get('umedida/{id}/editar','catumedidaController@actionEditarUmedida')->name('editarumedida');
    Route::put('umedida/{id}/update','catumedidaController@actionActualizarUmedida')->name('actualizarumedida');
    Route::get('umedida/{id}/Borrar','catumedidaController@actionBorrarUmedida')->name('borrarumedida');    
    Route::get('umedida/excel'      ,'catumedidaController@exportCatUmedidaExcel')->name('catumedidaexcel');
    Route::get('umedida/pdf'        ,'catumedidaController@exportCatUmedidaPdf')->name('catumedidapdf');    
    //Imnuebles edo.
    Route::get('inmuebleedo/nuevo'      ,'catalogosinmueblesedoController@actionNuevoInmuebleedo')->name('nuevoInmuebleedo');
    Route::post('inmuebleedo/nuevo/alta','catalogosinmueblesedoController@actionAltaNuevoInmuebleedo')->name('AltaNuevoInmuebleedo');
    Route::get('inmuebleedo/ver/todos'  ,'catalogosinmueblesedoController@actionVerInmuebleedo')->name('verInmuebleedo');
    Route::get('inmuebleedo/{id}/editar/rubro','catalogosinmueblesedoController@actionEditarInmuebleedo')->name('editarInmuebleedo');
    Route::put('inmuebleedo/{id}/actualizar'  ,'catalogosinmueblesedoController@actionActualizarInmuebleedo')->name('actualizarInmuebleedo');
    Route::get('inmuebleedo/{id}/Borrar','catalogosinmueblesedoController@actionBorrarInmuebleedo')->name('borrarInmuebleedo');
    Route::get('inmuebleedo/excel'      ,'catalogosinmueblesedoController@exportCatInmueblesedoExcel')->name('downloadinmueblesedo');
    Route::get('inmuebleedo/pdf'        ,'catalogosinmueblesedoController@exportCatInmueblesedoPdf')->name('catinmueblesedoPDF');
    //tipos de archivos
    Route::get('formato/nuevo'              ,'formatosController@actionNuevoFormato')->name('nuevoFormato');
    Route::post('formato/nuevo/alta'        ,'formatosController@actionAltaNuevoFormato')->name('AltaNuevoFormato');
    Route::get('formato/ver/todos'          ,'formatosController@actionVerFormatos')->name('verFormatos');
    Route::get('formato/{id}/editar/formato','formatosController@actionEditarFormato')->name('editarFormato');
    Route::put('formato/{id}/actualizar'    ,'formatosController@actionActualizarFormato')->name('actualizarFormato');
    Route::get('formato/{id}/Borrar'        ,'formatosController@actionBorrarFormato')->name('borrarFormato');    
    Route::get('formato/excel'              ,'formatosController@exportCatRubrosExcel')->name('downloadrubros');
    Route::get('formato/pdf'                ,'formatosController@exportCatRubrosPdf')->name('catrubrosPDF');     

    //catalogo de documentos
    Route::get('docto/buscar/todos'        ,'doctosController@actionBuscarDocto')->name('buscarDocto');    
    Route::get('docto/nuevo'               ,'doctosController@actionNuevoDocto')->name('nuevoDocto');
    Route::post('docto/nuevo/alta'         ,'doctosController@actionAltaNuevoDocto')->name('AltaNuevoDocto');
    Route::get('docto/ver/todos'           ,'doctosController@actionVerDoctos')->name('verDoctos');
    Route::get('docto/{id}/editar/formato' ,'doctosController@actionEditarDocto')->name('editarDocto');
    Route::put('docto/{id}/actualizar'     ,'doctosController@actionActualizarDocto')->name('actualizarDocto');    
    Route::get('docto/{id}/editar/formato1','doctosController@actionEditarDocto1')->name('editarDocto1');
    Route::put('docto/{id}/actualizar1'    ,'doctosController@actionActualizarDocto1')->name('actualizarDocto1');
    Route::get('docto/{id}/Borrar'         ,'doctosController@actionBorrarDocto')->name('borrarDocto');    
    Route::get('docto/excel'               ,'doctosController@exportCatDoctosExcel')->name('catDoctosExcel');
    Route::get('docto/pdf'                 ,'doctosController@exportCatDoctosPdf')->name('catDoctosPDF');     

    //Municipios sedesem
    Route::get('municipio/ver/todos','catalogosmunicipiosController@actionVermunicipios')->name('verMunicipios');
    Route::get('municipio/excel'    ,'catalogosmunicipiosController@exportCatmunicipiosExcel')->name('downloadmunicipios');
    Route::get('municipio/pdf'      ,'catalogosmunicipiosController@exportCatmunicipiosPdf')->name('catmunicipiosPDF');
    
    //OSC
    //Directorio
    Route::get('oscs/nueva'           ,'oscController@actionNuevaOsc')->name('nuevaOsc');
    Route::post('oscs/nueva/alta'     ,'oscController@actionAltaNuevaOsc')->name('AltaNuevaOsc');
    Route::get('oscs/ver/todas'       ,'oscController@actionVerOsc')->name('verOsc');
    Route::get('oscs/buscar/todas'    ,'oscController@actionBuscarOsc')->name('buscarOsc');    
    Route::get('oscs/{id}/editar/oscs','oscController@actionEditarOsc')->name('editarOsc');
    Route::put('oscs/{id}/actualizar' ,'oscController@actionActualizarOsc')->name('actualizarOsc');
    Route::get('oscs/{id}/Borrar'     ,'oscController@actionBorrarOsc')->name('borrarOsc');
    Route::get('oscs/excel'           ,'oscController@exportOscExcel')->name('oscexcel');
    Route::get('oscs/pdf'             ,'oscController@exportOscPdf')->name('oscPDF');

    Route::get('oscs/{id}/editar/osc1','oscController@actionEditarOsc1')->name('editarOsc1');
    Route::put('oscs/{id}/actualizar1','oscController@actionActualizarOsc1')->name('actualizarOsc1'); 
    Route::get('oscs/{id}/editar/osc2','oscController@actionEditarOsc2')->name('editarOsc2');
    Route::put('oscs/{id}/actualizar2','oscController@actionActualizarOsc2')->name('actualizarOsc2');        
 
    Route::get('oscs5/ver/todas'       ,'oscController@actionVerOsc5')->name('verOsc5');
    Route::get('oscs5/{id}/editar/oscs','oscController@actionEditarOsc5')->name('editarOsc5');
    Route::put('oscs5/{id}/actualizar' ,'oscController@actionActualizarOsc5')->name('actualizarOsc5');    
      
    //Requisitos Jurídicos
    Route::get('rjuridicos/nueva'              ,'rJuridicosController@actionNuevaJur')->name('nuevaJur');
    Route::post('rjuridicos/nueva/alta'        ,'rJuridicosController@actionAltaNuevaJur')->name('AltaNuevaJur');  
    Route::get('rjuridicos/buscar/todos'       ,'rJuridicosController@actionBuscarJur')->name('buscarJur');          
    Route::get('rjuridicos/ver/todasj'         ,'rJuridicosController@actionVerJur')->name('verJur');
    Route::get('rjuridicos/{id}/editar/jur'  ,'rJuridicosController@actionEditarJur')->name('editarJur');
    Route::put('rjuridicos/{id}/actualizarj'   ,'rJuridicosController@actionActualizarJur')->name('actualizarJur'); 
    Route::get('rjuridicos/{id}/Borrarj'       ,'rJuridicosController@actionBorrarJur')->name('borrarJur');

    Route::get('rjuridicos/{id}/editar/rjur12','rJuridicosController@actionEditarJur12')->name('editarJur12');
    Route::put('rjuridicos/{id}/actualizarj12','rJuridicosController@actionActualizarJur12')->name('actualizarJur12');    
    Route::get('rjuridicos/{id}/editar/rjur13','rJuridicosController@actionEditarJur13')->name('editarJur13');
    Route::put('rjuridicos/{id}/actualizarj13','rJuridicosController@actionActualizarJur13')->name('actualizarJur13'); 
    Route::get('rjuridicos/{id}/editar/rjur14','rJuridicosController@actionEditarJur14')->name('editarJur14');
    Route::put('rjuridicos/{id}/actualizarj14','rJuridicosController@actionActualizarJur14')->name('actualizarJur14');
    Route::get('rjuridicos/{id}/editar/rjur15','rJuridicosController@actionEditarJur15')->name('editarJur15');
    Route::put('rjuridicos/{id}/actualizarj15','rJuridicosController@actionActualizarJur15')->name('actualizarJur15');

    //Requisitos de operación
    //Padron de beneficiarios
    Route::get('padron/nueva'           ,'padronController@actionNuevoPadron')->name('nuevoPadron');
    Route::post('padron/nueva/alta'     ,'padronController@actionAltaNuevoPadron')->name('AltaNuevoPadron');
    Route::get('padron/ver/todas'       ,'padronController@actionVerPadron')->name('verPadron');
    Route::get('padron/buscar/todas'    ,'padronController@actionBuscarPadron')->name('buscarPadron');    
    Route::get('padron/{id}/editar/padron','padronController@actionEditarPadron')->name('editarPadron');
    Route::put('padron/{id}/actualizar' ,'padronController@actionActualizarPadron')->name('actualizarPadron');
    Route::get('padron/{id}/Borrar'     ,'padronController@actionBorrarPadron')->name('borrarPadron');
    Route::get('padron/excel'           ,'padronController@actionExportPadronExcel')->name('ExportPadronExcel');
    Route::get('padron/pdf'             ,'padronController@actionExportPadronPdf')->name('ExportPadronPdf');

    //Programa anual (PA)
    Route::get('programa/nuevo'         ,'proganualController@actionNuevoPA')->name('nuevopa');
    Route::post('programa/alta'         ,'proganualController@actionAltaNuevoPA')->name('altanuevopa');
    Route::get('programa/ver'           ,'proganualController@actionVerPA')->name('verpa');
    Route::get('programa/buscar'        ,'proganualController@actionBuscarPA')->name('buscarpa');    
    Route::get('programa/{id}/editar'   ,'proganualController@actionEditarPA')->name('editarpa');
    Route::put('programa/{id}/update'   ,'proganualController@actionActualizarPA')->name('actualizarpa');
    Route::get('programa/{id}/Borrar'   ,'proganualController@actionBorrarPA')->name('borrarpa');
    Route::get('programa/excel/{id}'    ,'proganualController@actionExportPAExcel')->name('exportpaexcel');
    Route::get('programa/pdf/{id}/{id2}','proganualController@actionExportPAPDF')->name('exportpapdf');

    Route::get('programad/{id}/nuevo'        ,'proganualController@actionNuevodPA')->name('nuevodpa');
    Route::post('programad/alta'             ,'proganualController@actionAltaNuevodPA')->name('altanuevodpa');
    Route::get('programad/{id}/ver'          ,'proganualController@actionVerdPA')->name('verdpa');
    Route::get('programad/{id}/{id2}/editar' ,'proganualController@actionEditardPA')->name('editardpa');
    Route::put('programad/{id}/{id2}/update' ,'proganualController@actionActualizardPA')->name('actualizardpa');
    Route::get('programad/{id}/{id2}/Borrar' ,'proganualController@actionBorrardPA')->name('borrardpa');

    Route::get('programad/{id}/{id2}/editar1','proganualController@actionEditardPA1')->name('editardpa1');
    Route::put('programad/{id}/{id2}/update1','proganualController@actionActualizardPA1')->name('actualizardpa1');    

    //Programa de trabajo PA - Avances
    //Route::get('informe/nuevo'           ,'informeController@actionNuevoInforme')->name('nuevoInforme');
    //Route::post('informe/nuevo/alta'     ,'informeController@actionAltaNuevoInforme')->name('AltaNuevoInforme');
    Route::get('informe/ver/todos'       ,'informeController@actionVerInformes')->name('verInformes');
    Route::get('informe/buscar/todos'    ,'informeController@actionBuscarInforme')->name('buscarInforme');    
    //Route::get('informe/{id}/editar/inflab','informeController@actionEditarInforme')->name('editarInforme');
    //Route::put('informe/{id}/actualizar' ,'informeController@actionActualizarInforme')->name('actualizarInforme');
    //Route::get('informe/{id}/Borrar'     ,'informeController@actionBorrarInforme')->name('borrarInforme');
    //Route::get('informe/excel/{id}'      ,'informeController@actionExportInformeExcel')->name('ExportInformeExcel');
    Route::get('informe/pdf/{id}/{id2}'  ,'informeController@actionExportInformePdf')->name('ExportInformePdf');

    Route::get('informe/{id}/ver/todosi','informeController@actionVerInformelab')->name('verInformelab');
    //Route::get('informe/{id}/nuevo'     ,'informeController@actionNuevoInformelab')->name('nuevoInformelab');
    //Route::post('informe/nuevo/alta'    ,'informeController@actionAltaNuevoInformelab')->name('altaNuevoInformelab'); 
    Route::get('informe/{id}/{id2}/editar' ,'informeController@actionEditarInformelab')->name('editarInformelab');
    Route::put('informe/{id}/{id2}/update' ,'informeController@actionActualizarInformelab')->name('actualizarInformelab');
    Route::get('informe/{id}/{id2}/editar1','informeController@actionEditarInformelab1')->name('editarInformelab1');
    Route::put('informe/{id}/{id2}/update1','informeController@actionActualizarInformelab1')->name('actualizarInformelab1');

    //Programa de trabajo PA - Soportes documentales mensuales
    Route::get('soporte/ver/todos'         ,'soportesController@actionVerSoportes')->name('versoportes');
    Route::get('soporte/buscar/todos'      ,'soportesController@actionBuscarSoporte')->name('buscarsoporte');        
    Route::get('soporte/{id}/ver/todosi'   ,'soportesController@actionVerSoportesdet')->name('versoportesdet');
    Route::get('soporte/{id}/{id2}/editar' ,'soportesController@actionEditarSoportedet')->name('editarsoportedet');
    Route::put('soporte/{id}/{id2}/update' ,'soportesController@actionActualizarSoportedet')->name('actualizarsoportedet');

    Route::get('soporte/{id}/{id2}/editar1' ,'soportesController@actionEditarSoportedet1')->name('editarsoportedet1');
    Route::put('soporte/{id}/{id2}/update1' ,'soportesController@actionActualizarSoportedet1')->name('actualizarsoportedet1');
    Route::get('soporte/{id}/{id2}/editar2' ,'soportesController@actionEditarSoportedet2')->name('editarsoportedet2');
    Route::put('soporte/{id}/{id2}/update2' ,'soportesController@actionActualizarSoportedet2')->name('actualizarsoportedet2');
    Route::get('soporte/{id}/{id2}/editar3' ,'soportesController@actionEditarSoportedet3')->name('editarsoportedet3');
    Route::put('soporte/{id}/{id2}/update3' ,'soportesController@actionActualizarSoportedet3')->name('actualizarsoportedet3');
    Route::get('soporte/{id}/{id2}/editar4' ,'soportesController@actionEditarSoportedet4')->name('editarsoportedet4');
    Route::put('soporte/{id}/{id2}/update4' ,'soportesController@actionActualizarSoportedet4')->name('actualizarsoportedet4');    
    Route::get('soporte/{id}/{id2}/editar5' ,'soportesController@actionEditarSoportedet5')->name('editarsoportedet5');
    Route::put('soporte/{id}/{id2}/update5' ,'soportesController@actionActualizarSoportedet5')->name('actualizarsoportedet5');
    Route::get('soporte/{id}/{id2}/editar6' ,'soportesController@actionEditarSoportedet6')->name('editarsoportedet6');
    Route::put('soporte/{id}/{id2}/update6' ,'soportesController@actionActualizarSoportedet6')->name('actualizarsoportedet6');
    Route::get('soporte/{id}/{id2}/editar7' ,'soportesController@actionEditarSoportedet7')->name('editarsoportedet7');
    Route::put('soporte/{id}/{id2}/update7' ,'soportesController@actionActualizarSoportedet7')->name('actualizarsoportedet7');
    Route::get('soporte/{id}/{id2}/editar8' ,'soportesController@actionEditarSoportedet8')->name('editarsoportedet8');
    Route::put('soporte/{id}/{id2}/update8' ,'soportesController@actionActualizarSoportedet8')->name('actualizarsoportedet8');    
    Route::get('soporte/{id}/{id2}/editar9' ,'soportesController@actionEditarSoportedet9')->name('editarsoportedet9');
    Route::put('soporte/{id}/{id2}/update9' ,'soportesController@actionActualizarSoportedet9')->name('actualizarsoportedet9');    
    Route::get('soporte/{id}/{id2}/editar10','soportesController@actionEditarSoportedet10')->name('editarsoportedet10');
    Route::put('soporte/{id}/{id2}/update10','soportesController@actionActualizarSoportedet10')->name('actualizarsoportedet10');            
    Route::get('soporte/{id}/{id2}/editar11','soportesController@actionEditarSoportedet11')->name('editarsoportedet11');
    Route::put('soporte/{id}/{id2}/update11','soportesController@actionActualizarSoportedet11')->name('actualizarsoportedet11'); 
    Route::get('soporte/{id}/{id2}/editar12','soportesController@actionEditarSoportedet12')->name('editarsoportedet12');
    Route::put('soporte/{id}/{id2}/update12','soportesController@actionActualizarSoportedet12')->name('actualizarsoportedet12');     
    //Requisitos administrativos
    // Otros requisitos admon.  
    Route::get('rcontables/ver/todasc'         ,'rContablesController@actionVerReqc')->name('verReqc');
    Route::get('rcontables/buscar/todos'       ,'rContablesController@actionBuscarReqc')->name('buscarReqc');        
    Route::get('rcontables/nueva'              ,'rContablesController@actionNuevoReqc')->name('nuevoReqc');
    Route::post('rcontables/nueva/alta'        ,'rContablesController@actionAltaNuevoReqc')->name('AltaNuevoReqc');      
    Route::get('rcontables/{id}/editar/reqc'   ,'rContablesController@actionEditarReqc')->name('editarReqc');
    Route::put('rcontables/{id}/actualizarreqc','rContablesController@actionActualizarReqc')->name('actualizarReqc'); 
    Route::get('rcontables/{id}/Borrarreqc'    ,'rContablesController@actionBorrarReqc')->name('borrarReqc');

    Route::get('rcontables/{id}/editar/reqc6'   ,'rContablesController@actionEditarReqc6')->name('editarReqc6');
    Route::put('rcontables/{id}/actualizarreqc6','rContablesController@actionActualizarReqc6')->name('actualizarReqc6');    

    Route::get('rcontables/{id}/editar/reqc7'   ,'rContablesController@actionEditarReqc7')->name('editarReqc7');
    Route::put('rcontables/{id}/actualizarreqc7','rContablesController@actionActualizarReqc7')->name('actualizarReqc7'); 

    Route::get('rcontables/{id}/editar/reqc8'   ,'rContablesController@actionEditarReqc8')->name('editarReqc8');
    Route::put('rcontables/{id}/actualizarreqc8','rContablesController@actionActualizarReqc8')->name('actualizarReqc8');    

    Route::get('rcontables/{id}/editar/reqc9'   ,'rContablesController@actionEditarReqc9')->name('editarReqc9');
    Route::put('rcontables/{id}/actualizarreqc9','rContablesController@actionActualizarReqc9')->name('actualizarReqc9');    

    Route::get('rcontables/{id}/editar/reqc11'   ,'rContablesController@actionEditarReqc11')->name('editarReqc11');
    Route::put('rcontables/{id}/actualizarreqc11','rContablesController@actionActualizarReqc11')->name('actualizarReqc11');  

    Route::get('rcontables/{id}/editar/reqc10'   ,'rContablesController@actionEditarReqc10')->name('editarReqc10');
    Route::put('rcontables/{id}/actualizarreqc10','rContablesController@actionActualizarReqc10')->name('actualizarReqc10');     
    Route::get('rcontables/{id}/editar/reqc11'   ,'rContablesController@actionEditarReqc11')->name('editarReqc11'); 
    Route::put('rcontables/{id}/actualizarreqc11','rContablesController@actionActualizarReqc11')->name('actualizarReqc11');
    
    // quotas de 5 al millar meses
    Route::get('rcontables/{id}/editar/reqc1002'   ,'rContablesController@actionEditarReqc1002')->name('editarReqc1002');
    Route::put('rcontables/{id}/actualizarreqc1002','rContablesController@actionActualizarReqc1002')->name('actualizarReqc1002');
    Route::get('rcontables/{id}/editar/reqc1003'   ,'rContablesController@actionEditarReqc1003')->name('editarReqc1003');
    Route::put('rcontables/{id}/actualizarreqc1003','rContablesController@actionActualizarReqc1003')->name('actualizarReqc1003');
    Route::get('rcontables/{id}/editar/reqc1004'   ,'rContablesController@actionEditarReqc1004')->name('editarReqc1004');
    Route::put('rcontables/{id}/actualizarreqc1004','rContablesController@actionActualizarReqc1004')->name('actualizarReqc1004');
    Route::get('rcontables/{id}/editar/reqc1005'   ,'rContablesController@actionEditarReqc1005')->name('editarReqc1005');
    Route::put('rcontables/{id}/actualizarreqc1005','rContablesController@actionActualizarReqc1005')->name('actualizarReqc1005');
    Route::get('rcontables/{id}/editar/reqc1006'   ,'rContablesController@actionEditarReqc1006')->name('editarReqc1006');
    Route::put('rcontables/{id}/actualizarreqc1006','rContablesController@actionActualizarReqc1006')->name('actualizarReqc1006');
    Route::get('rcontables/{id}/editar/reqc1007'   ,'rContablesController@actionEditarReqc1007')->name('editarReqc1007');
    Route::put('rcontables/{id}/actualizarreqc1007','rContablesController@actionActualizarReqc1007')->name('actualizarReqc1007');
    Route::get('rcontables/{id}/editar/reqc1008'   ,'rContablesController@actionEditarReqc1008')->name('editarReqc1008');
    Route::put('rcontables/{id}/actualizarreqc1008','rContablesController@actionActualizarReqc1008')->name('actualizarReqc1008');
    Route::get('rcontables/{id}/editar/reqc1009'   ,'rContablesController@actionEditarReqc1009')->name('editarReqc1009');
    Route::put('rcontables/{id}/actualizarreqc1009','rContablesController@actionActualizarReqc1009')->name('actualizarReqc1009');
    Route::get('rcontables/{id}/editar/reqc1010'   ,'rContablesController@actionEditarReqc1010')->name('editarReqc1010');
    Route::put('rcontables/{id}/actualizarreqc1010','rContablesController@actionActualizarReqc1010')->name('actualizarReqc1010');
    Route::get('rcontables/{id}/editar/reqc1011'   ,'rContablesController@actionEditarReqc1011')->name('editarReqc1011');
    Route::put('rcontables/{id}/actualizarreqc1011','rContablesController@actionActualizarReqc1011')->name('actualizarReqc1011');
    Route::get('rcontables/{id}/editar/reqc1012'   ,'rContablesController@actionEditarReqc1012')->name('editarReqc1012');
    Route::put('rcontables/{id}/actualizarreqc1012','rContablesController@actionActualizarReqc1012')->name('actualizarReqc1012');

    // Validar y autorizar Incripción al RSE
    Route::get('validar/ver/irse'             ,'validarrseController@actionVerIrse')->name('verirse');
    Route::get('validar/buscar/irse'          ,'validarrseController@actionBuscarIrse')->name('buscarirse');  
    Route::get('validar/nueva'                ,'validarrseController@actionNuevoValrse')->name('nuevoValrse');
    Route::post('validar/nueva/alta'          ,'validarrseController@actionAltaNuevoValrse')->name('AltaNuevoValrse');    
    Route::get('validar/{id}/editar/valrse'   ,'validarrseController@actionEditarValrse')->name('editarValrse');
    Route::put('validar/{id}/actualizarvalrse','validarrseController@actionActualizarValrse')->name('actualizarValrse'); 
    Route::get('validar/{id}/BorrarValrse'    ,'validarrseController@actionBorrarValrse')->name('borrarValrse');
    Route::get('validar/{id}/{id2}/pdf'       ,'validarrseController@actionIrsePDF')->name('irsePDF');   
    
    //5. Solicitud de constancia de renovación del Objeto social
    Route::get('sconst/ver/todasc'          ,'constanciasController@actionVerConst')->name('verConst');
    Route::get('sconst/buscar/todos'        ,'constanciasController@actionBuscarConst')->name('buscarConst');        
    Route::get('sconst/nueva'               ,'constanciasController@actionNuevaConst')->name('nuevaConst');
    Route::post('sconst/nueva/alta'         ,'constanciasController@actionAltaNuevaConst')->name('AltaNuevaConst');      
    Route::get('sconst/{id}/editar/const'   ,'constanciasController@actionEditarConst')->name('editarConst');
    Route::put('sconst/{id}/actualizarconst','constanciasController@actionActualizarConst')->name('actualizarConst'); 
    Route::get('sconst/{id}/Borrarconst'    ,'constanciasController@actionBorrarConst')->name('borrarConst');

    Route::get('sconst/{id}/editar/const1'   ,'constanciasController@actionEditarConst1')->name('editarConst1');
    Route::put('sconst/{id}/actualizarconst1','constanciasController@actionActualizarConst1')->name('actualizarConst1'); 

    // Agenda
    //Programar diligencias
    Route::get('progdil/nuevo'           ,'progdilController@actionNuevoProgdil')->name('nuevoProgdil');
    Route::post('progdil/nuevo/alta'     ,'progdilController@actionAltaNuevoProgdil')->name('AltaNuevoProgdil');
    Route::get('progdil/ver/todas'       ,'progdilController@actionVerProgdil')->name('verProgdil');
    Route::get('progdil/buscar/todas'    ,'progdilController@actionBuscarProgdil')->name('buscarProgdil');    
    Route::get('progdil/{id}/editar/progdilig','progdilController@actionEditarProgdil')->name('editarProgdil');
    Route::put('progdil/{id}/actualizar' ,'progdilController@actionActualizarProgdil')->name('actualizarProgdil');
    Route::get('progdil/{id}/Borrar'     ,'progdilController@actionBorrarProgdil')->name('borrarProgdil');
    //Route::get('progdil/excel'           ,'progdilController@exportProgdilExcel')->name('ProgdilExcel');
    Route::get('progdil/{id}/pdf'        ,'progdilController@actionMandamientoPDF')->name('mandamientoPDF');

    Route::get('progdil/reporte/reportepv','progdilController@actionReporteProgvisitas')->name('reporteProgvisitas');
    Route::post('progdil/pdf/reportepv'   ,'progdilController@actionProgramavisitasPdf')->name('programavisitasPdf');
    Route::get('progdil/reporte/reporteexe' ,'progdilController@actionReporteProgvisitasExcel')->name('reporteProgvisitasExcel');
    Route::post('progdil/Excel//reporteexel','progdilController@actionProgramavisitasExcel')->name('programavisitasExcel');

    Route::get('reportes/exel/filtro1'   ,'reportespaController@actionRepExcelq01')->name('repexelq01');
    Route::post('reportes/Excel/reporte1','reportespaController@actionRepExcel01')->name('repexcel01');    

    //Visitas de diligencia
    Route::get('visitas/nueva'             ,'visitasController@actionNuevaVisita')->name('nuevaVisita');
    Route::post('visitas/nueva/alta'       ,'visitasController@actionAltaNuevaVisita')->name('altaNuevaVisita');
    Route::get('visitas/ver/todas'         ,'visitasController@actionVerVisitas')->name('verVisitas');
    Route::get('visitas/buscar/todas'      ,'visitasController@actionBuscarVisita')->name('buscarVisita');    
    Route::get('visitas/{id}/editar/visita','visitasController@actionEditarVisita')->name('editarVisita');
    Route::put('visitas/{id}/actualizar'   ,'visitasController@actionActualizarVisita')->name('actualizarVisita');
    Route::get('visitas/{id}/Borrar'       ,'visitasController@actionBorrarVisita')->name('borrarVisita');   
    //Route::get('visitas/excel'           ,'visitasController@exportVisitasExcel')->name('VisitasExcel');
    Route::get('visitas/{id}/pdf'          ,'visitasController@actionActaVisitaPDF')->name('actavisitaPDF'); 

    //Indicadores
    Route::get('indicador/ver/todos'        ,'indicadoresController@actionVerCumplimiento')->name('vercumplimiento');
    Route::get('indicador/buscar/todos'     ,'indicadoresController@actionBuscarCumplimiento')->name('buscarcumplimiento');    
    Route::get('indicador/ver/todamatriz'   ,'indicadoresController@actionVermatrizCump')->name('vermatrizcump');
    Route::get('indicador/buscar/matriz'    ,'indicadoresController@actionBuscarmatrizCump')->name('buscarmatrizcump');      
    Route::get('indicador/ver/todasvisitas' ,'indicadoresController@actionVerCumplimientovisitas')->name('vercumplimientovisitas');
    Route::get('indicador/buscar/allvisitas','indicadoresController@actionBuscarCumplimientovisitas')->name('buscarcumplimientovisitas');    
    Route::get('indicador/{id}/oficiopdf'   ,'indicadoresController@actionOficioInscripPdf')->name('oficioInscripPdf'); 

    //Estadísticas
    //OSC
    Route::get('numeralia/graficaixedo'   ,'estadisticaOscController@OscxEdo')->name('oscxedo');
    Route::get('numeralia/graficaixmpio'  ,'estadisticaOscController@OscxMpio')->name('oscxmpio');
    Route::get('numeralia/graficaixrubro' ,'estadisticaOscController@OscxRubro')->name('oscxrubro');    
    Route::get('numeralia/graficaixrubro2','estadisticaOscController@OscxRubro2')->name('oscxrubro2'); 
    Route::get('numeralia/filtrobitacora' ,'estadisticaOscController@actionVerBitacora')->name('verbitacora');        
    Route::post('numeralia/estadbitacora' ,'estadisticaOscController@Bitacora')->name('bitacora'); 
    Route::get('numeralia/mapaxmpio'      ,'estadisticaOscController@actiongeorefxmpio')->name('georefxmpio');            
    Route::get('numeralia/mapas'          ,'estadisticaOscController@Mapas')->name('verMapas');        
    Route::get('numeralia/mapas2'         ,'estadisticaOscController@Mapas2')->name('verMapas2');        
    Route::get('numeralia/mapas3'         ,'estadisticaOscController@Mapas3')->name('verMapas3');        

    //padrón
    Route::get('numeralia/graficpadxedo'    ,'estadisticaPadronController@actionPadronxEdo')->name('padronxedo');
    //Route::get('numeralia/graficpadxmpio' ,'estadisticaPadronController@actionPadronxMpio')->name('padronxmpio');
    Route::get('numeralia/graficpadxserv'   ,'estadisticaPadronController@actionPadronxServicio')->name('padronxservicio');
    Route::get('numeralia/graficpadxsexo'   ,'estadisticaPadronController@actionPadronxsexo')->name('padronxsexo');
    Route::get('numeralia/graficpadxedad'   ,'estadisticaPadronController@actionPadronxedad')->name('padronxedad');
    Route::get('numeralia/graficpadxranedad','estadisticaPadronController@actionPadronxRangoedad')->name('padronxrangoedad');

    //Agenda
    Route::get('numeralia/graficaagenda1'     ,'progdilController@actionVerProgdilGraficaxmes')->name('verprogdilgraficaxmes');    
    Route::post('numeralia/graficaagendaxmes' ,'progdilController@actionProgdilGraficaxmes')->name('progdilgraficaxmes');
    Route::get('numeralia/graficaagenda2'     ,'progdilController@actionVerprogdilGraficaxtipo')->name('verprogdilgraficaxtipo');        
    Route::post('numeralia/graficaagendaxtipo','progdilController@actionProgdilGraficaxtipo')->name('progdilgraficaxtipo');

    // Email related routes
    Route::get('mail/ver/todos'        ,'mailController@actionVerContactos')->name('vercontactos');
    Route::get('mail/buscar/todos'     ,'mailController@actionBuscarContactos')->name('buscarcontactos');    
    Route::get('mail/{id}/editar/email','mailController@actionEditarEmail')->name('editaremail');
    //Route::put('mail/{id}/email'     ,'mailController@actionEmail')->name('Email'); 

    Route::get('mail/email'            ,'mailController@actionEmail')->name('Email'); 
    Route::put('mail/emailbienvenida'  ,'mailController@actionEmailBienve')->name('emailbienve'); 
    //Route::post('mail/send'          ,'mailController@send')->name('send');     
});

