@extends('sicinar.principal')

@section('title','Ver informe avances de acciones o metas del programa anual')

@section('links')
    <link rel="stylesheet" href="{{ asset('bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endsection

@section('nombre')
    {{$nombre}}
@endsection

@section('usuario')
    {{$usuario}}
@endsection

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>Registro - Registro de avances (PA)
                <small> Seleccionar para editar </small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Menú</a></li>
                <li><a href="#">Registro - </a></li>   
                <li><a href="#">Registro de vances (PA)     </a></li>               
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">

                        <table id="tabla1" class="table table-hover table-striped">
                        @foreach($regprogeanual as $encpa)
                            <tr>
                                <td style="text-align:right; vertical-align: middle;"> </td>
                                <td style="text-align:right; vertical-align: middle;"> 
                                   
                                   
                                    <a href="{{route('verInformes')}}" role="button" id="cancelar" class="btn btn-success"><small>Regresar a informe de avances </small>
                                    </a>
                                   
                                </td>                                     
                            </tr>                                                   

                            <tr>
                                <td style="border:0; text-align:left;font-size:10px;" >
                                <input type="hidden" id="periodo_id"     name="periodo_id"     value="{{$encpa->periodo_id}}">  
                                <input type="hidden" id="fecha_entrega"  name="fecha_entrega"  value="{{$encpa->fecha_entrega}}">  
                                <input type="hidden" id="fecha_entrega2" name="fecha_entrega2" value="{{$encpa->fecha_entrega2}}">  
                                <input type="hidden" id="fecha_entrega3" name="fecha_entrega3" value="{{$encpa->fecha_entrega3}}">  
                                <input type="hidden" id="depen_id1"      name="depen_id1"      value="{{$encpa->depen_id1}}">                                  
                                <input type="hidden" id="depen_id2"      name="depen_id2"      value="{{$encpa->depen_id2}}">  
                                <input type="hidden" id="epproy_id"      name="epproy_id"      value="{{$encpa->epproy_id}}">        
                                <input type="hidden" id="epprog_id"      name="epprog_id"      value="{{$encpa->epprog_id}}">                                                            
                                <input type="hidden" id="folio"          name="folio"          value="{{$encpa->folio}}">  

                                Programa: <b style="text-align:left; vertical-align: middle; color:green;font-size:10px;">
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                &nbsp;&nbsp;&nbsp;&nbsp;{{$encpa->epprog_id}}&nbsp;&nbsp;
                                @foreach($regepprog as $prog)
                                    @if($prog->epprog_id == $encpa->epprog_id)
                                        {{Trim($prog->epprog_desc)}}
                                        @break
                                    @endif
                                @endforeach
                                </b><br>                    
                                Proyecto: <b style="text-align:left; vertical-align: middle; color:green;font-size:10px;">
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                &nbsp;&nbsp;&nbsp;&nbsp;{{$encpa->epproy_id}}&nbsp;&nbsp;
                                @foreach($regepproy as $proy)
                                    @if($proy->epproy_id == $encpa->epproy_id)
                                        {{Trim($proy->epproy_desc)}}
                                        @break
                                    @endif
                                @endforeach
                                </b><br>                    
                                Unidad responsable: <b style="text-align:left; vertical-align: middle; color:green;font-size:10px;">
                                &nbsp;&nbsp;&nbsp;&nbsp;{{$encpa->depen_id1}}&nbsp;&nbsp;
                                @foreach($regdepen as $depen)
                                    @if($depen->depen_id == $encpa->depen_id1)
                                        {{Trim($depen->depen_desc)}}
                                        @break
                                    @endif
                                @endforeach
                                </b><br>                    
                                Unidad ejecutora: <b style="text-align:left; vertical-align: middle; color:green;font-size:10px;">
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                &nbsp;&nbsp;&nbsp;&nbsp;{{$encpa->depen_id2}}&nbsp;&nbsp;
                                @foreach($regdepen as $depen)
                                    @if($depen->depen_id == $encpa->depen_id2)
                                        {{Trim($depen->depen_desc)}}
                                        @break
                                    @endif
                                @endforeach
                                </b>              
                                </td>

                                <td style="border:0; text-align:left;font-size:10px;">
                                Fecha de entrega:<b style="text-align:left; vertical-align: middle; color:green;font-size:10px;">
                                {{$encpa->fecha_entrega2}}
                                </b>
                                <br>
                                Tipo: <b style="text-align:left; vertical-align: middle; color:green;font-size:10px;">
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                @foreach($regtipometa as $tipo)
                                    @if($tipo->taccion_id == $encpa->taccion_id)
                                       {{$tipo->taccion_desc}}
                                       @break 
                                    @endif
                                @endforeach
                                </b>
                                <br>

                                Periodo fiscal:<b style="text-align:left; vertical-align: middle; color:green;font-size:10px;">
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                {{$encpa->periodo_id}}</b><br>
                                Folio:<b style="text-align:left; vertical-align: middle; color:green;font-size:10px;">
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                {{$encpa->folio}}</b>
                                </td>
                            </tr>
                            @endforeach    
                        </table>

                        <div class="box-body">
                            <table id="tabla1" class="table table-hover table-striped">
                                <thead style="color: brown;" class="justify">
                                    <tr>
                                        <th style="text-align:left;   vertical-align: middle;font-size:11px;">      </th>
                                        <th style="text-align:left;   vertical-align: middle;font-size:11px;">No.  </th>
                                        <th style="text-align:left;   vertical-align: middle;font-size:11px;">Id.   </th>
                                        <th style="text-align:left;   vertical-align: middle;font-size:11px;">      </th>
                                        <th style="text-align:left;   vertical-align: middle;">Unidad               </th>
                                        <th colspan="13" style="text-align:center;vertical-align:middle;font-size:11px;">Metas / Avances / % / Semáforo</th>
                                        <th style="text-align:left;   vertical-align: middle;">                     </th>
                                        <th style="text-align:left;   vertical-align: middle;">                     </th>
                                    </tr>                                
                                    <tr>
                                        <th style="text-align:left;   vertical-align: middle;font-size:11px;">#                </th>
                                        <th style="text-align:left;   vertical-align: middle;font-size:11px;">IGOB   </th>
                                        <th style="text-align:left;   vertical-align: middle;font-size:11px;">SPP    </th>                                                                                
                                        <th style="text-align:left;   vertical-align: middle;font-size:11px;">Acción o meta    </th>
                                        <th style="text-align:left;   vertical-align: middle;font-size:11px;">Medida </th>
                                        <th style="text-align:left;   vertical-align: middle;font-size:11px;">Anual            </th>
                                        <th style="text-align:left;   vertical-align: middle;font-size:11px;">Ene              </th>
                                        <th style="text-align:left;   vertical-align: middle;font-size:11px;">Feb              </th>
                                        <th style="text-align:left;   vertical-align: middle;font-size:11px;">Mar              </th> 
                                        <th style="text-align:left;   vertical-align: middle;font-size:11px;">Abr              </th>
                                        <th style="text-align:left;   vertical-align: middle;font-size:11px;">May              </th>
                                        <th style="text-align:left;   vertical-align: middle;font-size:11px;">Jun              </th>
                                        <th style="text-align:left;   vertical-align: middle;font-size:11px;">Jul              </th>
                                        <th style="text-align:left;   vertical-align: middle;font-size:11px;">Ago              </th>
                                        <th style="text-align:left;   vertical-align: middle;font-size:11px;">Sep              </th>
                                        <th style="text-align:left;   vertical-align: middle;font-size:11px;">Oct              </th>
                                        <th style="text-align:left;   vertical-align: middle;font-size:11px;">Nov              </th>
                                        <th style="text-align:left;   vertical-align: middle;font-size:11px;">Dic              </th>
                                        <th style="text-align:center; vertical-align: middle;font-size:11px;width:100px;">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($regprogdanual as $dpa)
                                    <tr>
                                        <td style="text-align:left; vertical-align: middle;font-size:10px;">{{$dpa->partida}}</td>
                                        <td style="text-align:left; vertical-align: middle;font-size:10px;">{{trim($dpa->lgob_cod)}} </td>
                                        <td style="text-align:left; vertical-align: middle;font-size:10px;">{{trim($dpa->ciprep_id)}}</td>
                                        <td style="text-align:left; vertical-align: middle;font-size:10px;">{{Trim($dpa->actividad_desc)}}</td>
                                        
                                        <td style="text-align:left; vertical-align: middle;font-size:10px;">   
                                        @foreach($regumedida as $umedida)
                                            @if($umedida->umed_id == $dpa->umed_id)
                                                {{$umedida->umed_desc}}
                                                @break                                        
                                            @endif
                                        @endforeach
                                        </td>                                                                            
                                        <td style="text-align:left; vertical-align: middle;font-size:10px;">{{number_format($dpa->totp_01,0)}}</td>
                                        <td style="text-align:left; vertical-align: middle;font-size:10px;">{{number_format($dpa->mesp_01,0)}}</td>
                                        <td style="text-align:left; vertical-align: middle;font-size:10px;">{{number_format($dpa->mesp_02,0)}}</td>
                                        <td style="text-align:left; vertical-align: middle;font-size:10px;">{{number_format($dpa->mesp_03,0)}}</td>
                                        <td style="text-align:left; vertical-align: middle;font-size:10px;">{{number_format($dpa->mesp_04,0)}}</td>
                                        <td style="text-align:left; vertical-align: middle;font-size:10px;">{{number_format($dpa->mesp_05,0)}}</td>
                                        <td style="text-align:left; vertical-align: middle;font-size:10px;">{{number_format($dpa->mesp_06,0)}}</td>
                                        <td style="text-align:left; vertical-align: middle;font-size:10px;">{{number_format($dpa->mesp_07,0)}}</td>
                                        <td style="text-align:left; vertical-align: middle;font-size:10px;">{{number_format($dpa->mesp_08,0)}}</td>
                                        <td style="text-align:left; vertical-align: middle;font-size:10px;">{{number_format($dpa->mesp_09,0)}}</td>
                                        <td style="text-align:left; vertical-align: middle;font-size:10px;">{{number_format($dpa->mesp_10,0)}}</td>
                                        <td style="text-align:left; vertical-align: middle;font-size:10px;">{{number_format($dpa->mesp_11,0)}}</td>
                                        <td style="text-align:left; vertical-align: middle;font-size:10px;">{{number_format($dpa->mesp_12,0)}}</td>
                                        
                                        <td style="text-align:center;">
                                            <a href="{{route('editarInformelab',array($dpa->folio,$dpa->partida) )}}" class="btn badge-warning" title="Editar avance de la accón o meta del programa anual"><i class="fa fa-edit"></i>
                                            </a>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td style="color:#1a1c50;text-align:left; vertical-align: middle;font-size:9px;"></td>
                                        <td style="color:#1a1c50;text-align:left; vertical-align: middle;font-size:9px;"></td>
                                        <td style="color:#1a1c50;text-align:left; vertical-align: middle;font-size:9px;"></td>
                                        <td style="color:#1a1c50;text-align:left; vertical-align: middle;font-size:9px;">Avances</td>
                                        <td style="color:#1a1c50;text-align:left; vertical-align: middle;font-size:9px;"></td>
                                        <td style="color:#1a1c50;text-align:left; vertical-align: middle;font-size:10px;">{{number_format($dpa->totc_01,0)}}</td>
                                        <td style="color:#1a1c50;text-align:left; vertical-align: middle;font-size:10px;">{{number_format($dpa->mesc_01,0)}}</td>
                                        <td style="color:#1a1c50;text-align:left; vertical-align: middle;font-size:10px;">{{number_format($dpa->mesc_02,0)}}</td>
                                        <td style="color:#1a1c50;text-align:left; vertical-align: middle;font-size:10px;">{{number_format($dpa->mesc_03,0)}}</td>
                                        <td style="color:#1a1c50;text-align:left; vertical-align: middle;font-size:10px;">{{number_format($dpa->mesc_04,0)}}</td>
                                        <td style="color:#1a1c50;text-align:left; vertical-align: middle;font-size:10px;">{{number_format($dpa->mesc_05,0)}}</td>
                                        <td style="color:#1a1c50;text-align:left; vertical-align: middle;font-size:10px;">{{number_format($dpa->mesc_06,0)}}</td>
                                        <td style="color:#1a1c50;text-align:left; vertical-align: middle;font-size:10px;">{{number_format($dpa->mesc_07,0)}}</td>
                                        <td style="color:#1a1c50;text-align:left; vertical-align: middle;font-size:10px;">{{number_format($dpa->mesc_08,0)}}</td>
                                        <td style="color:#1a1c50;text-align:left; vertical-align: middle;font-size:10px;">{{number_format($dpa->mesc_09,0)}}</td>
                                        <td style="color:#1a1c50;text-align:left; vertical-align: middle;font-size:10px;">{{number_format($dpa->mesc_10,0)}}</td>
                                        <td style="color:#1a1c50;text-align:left; vertical-align: middle;font-size:10px;">{{number_format($dpa->mesc_11,0)}}</td>
                                        <td style="color:#1a1c50;text-align:left; vertical-align: middle;font-size:10px;">{{number_format($dpa->mesc_12,0)}}</td>

                                        <td style="color:darkgreen;text-align:center; vertical-align: middle;"></td>  
                                    </tr>           
                                    <tr>
                                        <td style="color:purple;text-align:left; vertical-align: middle;font-size:9px;"></td>
                                        <td style="color:purple;text-align:left; vertical-align: middle;font-size:9px;"></td>
                                        <td style="color:purple;text-align:left; vertical-align: middle;font-size:9px;"></td>
                                        <td style="color:purple;text-align:left; vertical-align: middle;font-size:9px;">%</td>
                                        <td style="color:purple;text-align:left; vertical-align: middle;font-size:9px;"></td>
                                        <td style="color:purple;text-align:left; vertical-align: middle;font-size:10px;">{{number_format($dpa->totp_01,2)}}</td>
                                        <td style="color:purple;text-align:left; vertical-align: middle;font-size:10px;">{{number_format($dpa->mes_p01,2)}}</td>
                                        <td style="color:purple;text-align:left; vertical-align: middle;font-size:10px;">{{number_format($dpa->mes_p02,2)}}</td>
                                        <td style="color:purple;text-align:left; vertical-align: middle;font-size:10px;">{{number_format($dpa->mes_p03,2)}}</td>
                                        <td style="color:purple;text-align:left; vertical-align: middle;font-size:10px;">{{number_format($dpa->mes_p04,2)}}</td>
                                        <td style="color:purple;text-align:left; vertical-align: middle;font-size:10px;">{{number_format($dpa->mes_p05,2)}}</td>
                                        <td style="color:purple;text-align:left; vertical-align: middle;font-size:10px;">{{number_format($dpa->mes_p06,2)}}</td>
                                        <td style="color:purple;text-align:left; vertical-align: middle;font-size:10px;">{{number_format($dpa->mes_p07,2)}}</td>
                                        <td style="color:purple;text-align:left; vertical-align: middle;font-size:10px;">{{number_format($dpa->mes_p08,2)}}</td>
                                        <td style="color:purple;text-align:left; vertical-align: middle;font-size:10px;">{{number_format($dpa->mes_p09,2)}}</td>
                                        <td style="color:purple;text-align:left; vertical-align: middle;font-size:10px;">{{number_format($dpa->mes_p10,2)}}</td>
                                        <td style="color:purple;text-align:left; vertical-align: middle;font-size:10px;">{{number_format($dpa->mes_p11,2)}}</td>
                                        <td style="color:purple;text-align:left; vertical-align: middle;font-size:10px;">{{number_format($dpa->mes_p12,2)}}</td>
                                        <td style="color:purple;text-align:left; vertical-align: middle;font-size:10px;"> </td>
                                    </tr>                                         
                                    <tr>
                                        <td style="color:purple;text-align:left; vertical-align: middle;font-size:9px;"></td>
                                        <td style="color:purple;text-align:left; vertical-align: middle;font-size:9px;"></td>
                                        <td style="color:purple;text-align:left; vertical-align: middle;font-size:9px;"></td>
                                        <td style="color:purple;text-align:left; vertical-align: middle;font-size:9px;">Semáforos</td>
                                        <td style="color:purple;text-align:left; vertical-align: middle;font-size:9px;"></td>   
                                        <td style="text-align:center;">  
                                        @switch($dpa->semafa_01)
                                        @case('1')
                                            <img src="{{ asset('images/semaforo_rojo.jpg') }}"    width="15px" height="15px" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                            @break
                                        @case('2')
                                            <img src="{{ asset('images/semaforo_naranja.jpg') }}" width="15px" height="15px" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                            @break
                                        @case('3')
                                            <img src="{{ asset('images/semaforo_amarillo.jpg')}}" width="15px" height="15px" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                            @break
                                        @case('4')
                                            <img src="{{ asset('images/semaforo_verde.jpg') }}"   width="15px" height="15px" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                            @break                                            
                                        @case('5')
                                            <img src="{{ asset('images/semaforo_morado.jpg') }}"  width="15px" height="15px" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                            @break                                                                          
                                        @default
                                            <img src="{{ asset('images/semaforo_rojo.jpg') }}"    width="15px" height="15px" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                        @endswitch
                                        </td>  

                                        <td style="text-align:center;">  
                                        @switch($dpa->semaf_01)
                                        @case('1')
                                            <img src="{{ asset('images/semaforo_rojo.jpg') }}"    width="15px" height="15px" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                            @break
                                        @case('2')
                                            <img src="{{ asset('images/semaforo_naranja.jpg') }}" width="15px" height="15px" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                            @break
                                        @case('3')
                                            <img src="{{ asset('images/semaforo_amarillo.jpg')}}" width="15px" height="15px" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                            @break
                                        @case('4')
                                            <img src="{{ asset('images/semaforo_verde.jpg') }}"   width="15px" height="15px" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                            @break                                            
                                        @case('5')
                                            <img src="{{ asset('images/semaforo_morado.jpg') }}"  width="15px" height="15px" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                            @break                                                                          
                                        @default
                                            <img src="{{ asset('images/semaforo_rojo.jpg') }}"    width="15px" height="15px" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                        @endswitch
                                        </td>  
                                        <td style="text-align:center;">
                                        @switch($dpa->semaf_02)
                                        @case('1')
                                            <img src="{{ asset('images/semaforo_rojo.jpg') }}"    width="15px" height="15px" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                            @break
                                        @case('2')
                                            <img src="{{ asset('images/semaforo_naranja.jpg') }}" width="15px" height="15px" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                            @break
                                        @case('3')
                                            <img src="{{ asset('images/semaforo_amarillo.jpg')}}" width="15px" height="15px" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                            @break
                                        @case('4')
                                            <img src="{{ asset('images/semaforo_verde.jpg') }}"   width="15px" height="15px" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                            @break                                            
                                        @case('5')
                                            <img src="{{ asset('images/semaforo_morado.jpg') }}"  width="15px" height="15px" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                            @break                                                                          
                                        @default
                                            <img src="{{ asset('images/semaforo_rojo.jpg') }}"    width="15px" height="15px" style="text-align:center;margin-right: 15px;vertical-align: middle;"/>
                                        @endswitch
                                        </td>  
                                        <td style="text-align:center;">  
                                        @switch($dpa->semaf_03)
                                        @case('1')
                                            <img src="{{ asset('images/semaforo_rojo.jpg') }}"    width="15px" height="15px" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                            @break
                                        @case('2')
                                            <img src="{{ asset('images/semaforo_naranja.jpg') }}" width="15px" height="15px" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                            @break
                                        @case('3')
                                            <img src="{{ asset('images/semaforo_amarillo.jpg')}}" width="15px" height="15px" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                            @break
                                        @case('4')
                                            <img src="{{ asset('images/semaforo_verde.jpg') }}"   width="15px" height="15px" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                            @break                                            
                                        @case('5')
                                            <img src="{{ asset('images/semaforo_morado.jpg') }}"  width="15px" height="15px" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                            @break                                                                          
                                        @default
                                            <img src="{{ asset('images/semaforo_rojo.jpg') }}"    width="15px" height="15px" style="text-align:center;margin-right: 15px;vertical-align: middle;"/>
                                        @endswitch
                                        </td>  
                                        <td style="text-align:center;">  
                                        @switch($dpa->semaf_04)
                                        @case('1')
                                            <img src="{{ asset('images/semaforo_rojo.jpg') }}"    width="15px" height="15px" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                            @break
                                        @case('2')
                                            <img src="{{ asset('images/semaforo_naranja.jpg') }}" width="15px" height="15px" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                            @break
                                        @case('3')
                                            <img src="{{ asset('images/semaforo_amarillo.jpg')}}" width="15px" height="15px" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                            @break
                                        @case('4')
                                            <img src="{{ asset('images/semaforo_verde.jpg') }}"   width="15px" height="15px" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                            @break                                            
                                        @case('5')
                                            <img src="{{ asset('images/semaforo_morado.jpg') }}"  width="15px" height="15px" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                            @break                                                                          
                                        @default
                                            <img src="{{ asset('images/semaforo_rojo.jpg') }}"    width="15px" height="15px" style="text-align:center;margin-right: 15px;vertical-align: middle;"/>
                                        @endswitch
                                        </td>  
                                        <td style="text-align:center;">  
                                        @switch($dpa->semaf_05)
                                        @case('1')
                                            <img src="{{ asset('images/semaforo_rojo.jpg') }}"    width="15px" height="15px" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                            @break
                                        @case('2')
                                            <img src="{{ asset('images/semaforo_naranja.jpg') }}" width="15px" height="15px" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                            @break
                                        @case('3')
                                            <img src="{{ asset('images/semaforo_amarillo.jpg')}}" width="15px" height="15px" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                            @break
                                        @case('4')
                                            <img src="{{ asset('images/semaforo_verde.jpg') }}"   width="15px" height="15px" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                            @break                                            
                                        @case('5')
                                            <img src="{{ asset('images/semaforo_morado.jpg') }}"  width="15px" height="15px" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                            @break                                                                          
                                        @default
                                            <img src="{{ asset('images/semaforo_rojo.jpg') }}"    width="15px" height="15px" style="text-align:center;margin-right: 15px;vertical-align: middle;"/>
                                        @endswitch
                                        </td>  
                                        <td style="text-align:center;">  
                                        @switch($dpa->semaf_06)
                                        @case('1')
                                            <img src="{{ asset('images/semaforo_rojo.jpg') }}"    width="15px" height="15px" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                            @break
                                        @case('2')
                                            <img src="{{ asset('images/semaforo_naranja.jpg') }}" width="15px" height="15px" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                            @break
                                        @case('3')
                                            <img src="{{ asset('images/semaforo_amarillo.jpg')}}" width="15px" height="15px" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                            @break
                                        @case('4')
                                            <img src="{{ asset('images/semaforo_verde.jpg') }}"   width="15px" height="15px" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                            @break                                            
                                        @case('5')
                                            <img src="{{ asset('images/semaforo_morado.jpg') }}"  width="15px" height="15px" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                            @break                                                                          
                                        @default
                                            <img src="{{ asset('images/semaforo_rojo.jpg') }}"    width="15px" height="15px" style="text-align:center;margin-right: 15px;vertical-align: middle;"/>
                                        @endswitch
                                        </td>    
                                        <td style="text-align:center;">  
                                        @switch($dpa->semaf_07)
                                        @case('1')
                                            <img src="{{ asset('images/semaforo_rojo.jpg') }}"    width="15px" height="15px" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                            @break
                                        @case('2')
                                            <img src="{{ asset('images/semaforo_naranja.jpg') }}" width="15px" height="15px" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                            @break
                                        @case('3')
                                            <img src="{{ asset('images/semaforo_amarillo.jpg')}}" width="15px" height="15px" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                            @break
                                        @case('4')
                                            <img src="{{ asset('images/semaforo_verde.jpg') }}"   width="15px" height="15px" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                            @break                                            
                                        @case('5')
                                            <img src="{{ asset('images/semaforo_morado.jpg') }}"  width="15px" height="15px" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                            @break                                                                          
                                        @default
                                            <img src="{{ asset('images/semaforo_rojo.jpg') }}"    width="15px" height="15px" style="text-align:center;margin-right: 15px;vertical-align: middle;"/>
                                        @endswitch
                                        </td>    
                                        <td style="text-align:center;">  
                                        @switch($dpa->semaf_08)
                                        @case('1')
                                            <img src="{{ asset('images/semaforo_rojo.jpg') }}"    width="15px" height="15px" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                            @break
                                        @case('2')
                                            <img src="{{ asset('images/semaforo_naranja.jpg') }}" width="15px" height="15px" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                            @break
                                        @case('3')
                                            <img src="{{ asset('images/semaforo_amarillo.jpg')}}" width="15px" height="15px" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                            @break
                                        @case('4')
                                            <img src="{{ asset('images/semaforo_verde.jpg') }}"   width="15px" height="15px" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                            @break                                            
                                        @case('5')
                                            <img src="{{ asset('images/semaforo_morado.jpg') }}"  width="15px" height="15px" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                            @break                                                                          
                                        @default
                                            <img src="{{ asset('images/semaforo_rojo.jpg') }}"    width="15px" height="15px" style="text-align:center;margin-right: 15px;vertical-align: middle;"/>
                                        @endswitch
                                        </td>    
                                        <td style="text-align:center;">  
                                        @switch($dpa->semaf_09)
                                        @case('1')
                                            <img src="{{ asset('images/semaforo_rojo.jpg') }}"    width="15px" height="15px" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                            @break
                                        @case('2')
                                            <img src="{{ asset('images/semaforo_naranja.jpg') }}" width="15px" height="15px" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                            @break
                                        @case('3')
                                            <img src="{{ asset('images/semaforo_amarillo.jpg')}}" width="15px" height="15px" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                            @break
                                        @case('4')
                                            <img src="{{ asset('images/semaforo_verde.jpg') }}"   width="15px" height="15px" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                            @break                                            
                                        @case('5')
                                            <img src="{{ asset('images/semaforo_morado.jpg') }}"  width="15px" height="15px" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                            @break                                                                          
                                        @default
                                            <img src="{{ asset('images/semaforo_rojo.jpg') }}"    width="15px" height="15px" style="text-align:center;margin-right: 15px;vertical-align: middle;"/>
                                        @endswitch
                                        </td>    
                                        <td style="text-align:center;">  
                                        @switch($dpa->semaf_10)
                                        @case('1')
                                            <img src="{{ asset('images/semaforo_rojo.jpg') }}"    width="15px" height="15px" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                            @break
                                        @case('2')
                                            <img src="{{ asset('images/semaforo_naranja.jpg') }}" width="15px" height="15px" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                            @break
                                        @case('3')
                                            <img src="{{ asset('images/semaforo_amarillo.jpg')}}" width="15px" height="15px" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                            @break
                                        @case('4')
                                            <img src="{{ asset('images/semaforo_verde.jpg') }}"   width="15px" height="15px" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                            @break                                            
                                        @case('5')
                                            <img src="{{ asset('images/semaforo_morado.jpg') }}"  width="15px" height="15px" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                            @break                                                                          
                                        @default
                                            <img src="{{ asset('images/semaforo_rojo.jpg') }}"    width="15px" height="15px" style="text-align:center;margin-right: 15px;vertical-align: middle;"/>
                                        @endswitch
                                        </td>    
                                        <td style="text-align:center;">  
                                        @switch($dpa->semaf_11)
                                        @case('1')
                                            <img src="{{ asset('images/semaforo_rojo.jpg') }}"    width="15px" height="15px" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                            @break
                                        @case('2')
                                            <img src="{{ asset('images/semaforo_naranja.jpg') }}" width="15px" height="15px" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                            @break
                                        @case('3')
                                            <img src="{{ asset('images/semaforo_amarillo.jpg')}}" width="15px" height="15px" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                            @break
                                        @case('4')
                                            <img src="{{ asset('images/semaforo_verde.jpg') }}"   width="15px" height="15px" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                            @break                                            
                                        @case('5')
                                            <img src="{{ asset('images/semaforo_morado.jpg') }}"  width="15px" height="15px" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                            @break                                                                          
                                        @default
                                            <img src="{{ asset('images/semaforo_rojo.jpg') }}"    width="15px" height="15px" style="text-align:center;margin-right: 15px;vertical-align: middle;"/>
                                        @endswitch
                                        </td>    
                                        <td style="text-align:center;">  
                                        @switch($dpa->semaf_12)
                                        @case('1')
                                            <img src="{{ asset('images/semaforo_rojo.jpg') }}"    width="15px" height="15px" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                            @break
                                        @case('2')
                                            <img src="{{ asset('images/semaforo_naranja.jpg') }}" width="15px" height="15px" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                            @break
                                        @case('3')
                                            <img src="{{ asset('images/semaforo_amarillo.jpg')}}" width="15px" height="15px" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                            @break
                                        @case('4')
                                            <img src="{{ asset('images/semaforo_verde.jpg') }}"   width="15px" height="15px" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                            @break                                            
                                        @case('5')
                                            <img src="{{ asset('images/semaforo_morado.jpg') }}"  width="15px" height="15px" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                            @break                                                                          
                                        @default
                                            <img src="{{ asset('images/semaforo_rojo.jpg') }}"    width="15px" height="15px" style="text-align:center;margin-right: 15px;vertical-align: middle;"/>
                                        @endswitch
                                        </td>    
                                        <td style="color:purple;text-align:left; vertical-align: middle;font-size:10px;"> </td>
                                    </tr>       
                                    @endforeach
                                </tbody>
                            </table>

                            <table id="tabla1" class="table table-hover table-striped">
                                <tr>
                                @foreach($regprogeanual as $encpa)
                                    <td style="text-align:left; vertical-align: middle; color:green;font-size:10px;">
                                        <label>Elaboró: </label>&nbsp;&nbsp;{{$encpa->responsable}}   
                                    </td>
                                    <td style="text-align:left; vertical-align: middle; color:green;font-size:10px;">   
                                        <label>Revisó: </label>&nbsp;&nbsp;{{$encpa->elaboro}}
                                    </td>                                            
                                    <td style="text-align:left; vertical-align: middle; color:green;font-size:10px;">   
                                        <label>Autorizó: </label>&nbsp;&nbsp;{{$encpa->autorizo}}
                                    </td>                                     
                                </tr>                                 
                                @endforeach
                            </table>

                            {!! $regprogdanual->appends(request()->input())->links() !!}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('request')
@endsection

@section('javascrpt')
@endsection
