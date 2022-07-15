@extends('sicinar.principal')

@section('title','Editar informe de acción o meta del programa anual')

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
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Menú
                <small> Programa anual (PA) -     </small>                
                <small> Avances - editar </small>           
            </h1>
        </section>
        <section class="content"> 
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-success">

                        {!! Form::open(['route' => ['actualizarInformelab',$regprogdanual->folio, $regprogdanual->partida], 'method' => 'PUT', 'id' => 'actualizarInformelab', 'enctype' => 'multipart/form-data']) !!}
                        <div class="box-body">

                            <table id="tabla1" class="table table-hover table-striped">
                            @foreach($regprogeanual as $encpa)
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
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$encpa->depen_id2}}&nbsp;&nbsp;
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

                            <table id="tabla1" class="table table-hover table-striped" >
                                <thead style="color: brown;" class="justify">
                                <tr>
                                    <th style="text-align:left;   vertical-align: middle;font-size:11px;">      </th>
                                    <th style="text-align:left;   vertical-align: middle;font-size:11px;">No.   </th>
                                    <th style="text-align:left;   vertical-align: middle;font-size:11px;">Id.   </th>
                                    <th style="text-align:left;   vertical-align: middle;font-size:11px;">      </th>
                                    <th style="text-align:left;   vertical-align: middle;">                     </th>
                                    <th colspan="12" style="text-align:center;vertical-align: middle;">Metas / Avances / % / Semáforo</th>
                                </tr>                                

                                <tr>
                                    <th style="text-align:left;   vertical-align: middle;font-size:11px;">#                </th>
                                    <th style="text-align:left;   vertical-align: middle;font-size:11px;">IGOB             </th>
                                    <th style="text-align:left;   vertical-align: middle;font-size:11px;">SPP              </th>                                                                                
                                    <th style="text-align:left;   vertical-align: middle;font-size:11px;">Acción o meta    </th>
                                    <th style="text-align:left;   vertical-align: middle;font-size:11px;">Unidad<br>Medida </th>
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
                                </tr>
                                </thead>
                                <tr>
                                    <td style="text-align:left; vertical-align: middle;font-size:10px;">{{$regprogdanual->partida}}</td>
                                    <td style="text-align:left; vertical-align: middle;font-size:10px;">{{trim($regprogdanual->lgob_cod)}} </td>
                                    <td style="text-align:left; vertical-align: middle;font-size:10px;">{{trim($regprogdanual->ciprep_id)}}</td>
                                    <td style="text-align:left; vertical-align: middle;font-size:10px;">{{Trim($regprogdanual->actividad_desc)}}</td>
                                        
                                    <td style="text-align:left; vertical-align: middle;font-size:10px;">   
                                        @foreach($regumedida as $umedida)
                                            @if($umedida->umed_id == $regprogdanual->umed_id)
                                                {{$umedida->umed_desc}}
                                                @break                                        
                                            @endif
                                        @endforeach
                                    </td>                                                                            
                                    <td style="text-align:left; vertical-align: middle;font-size:10px;">{{number_format($regprogdanual->mesp_01,0)}}</td>
                                    <td style="text-align:left; vertical-align: middle;font-size:10px;">{{number_format($regprogdanual->mesp_02,0)}}</td>
                                    <td style="text-align:left; vertical-align: middle;font-size:10px;">{{number_format($regprogdanual->mesp_03,0)}}</td>
                                    <td style="text-align:left; vertical-align: middle;font-size:10px;">{{number_format($regprogdanual->mesp_04,0)}}</td>
                                    <td style="text-align:left; vertical-align: middle;font-size:10px;">{{number_format($regprogdanual->mesp_05,0)}}</td>
                                    <td style="text-align:left; vertical-align: middle;font-size:10px;">{{number_format($regprogdanual->mesp_06,0)}}</td>
                                    <td style="text-align:left; vertical-align: middle;font-size:10px;">{{number_format($regprogdanual->mesp_07,0)}}</td>
                                    <td style="text-align:left; vertical-align: middle;font-size:10px;">{{number_format($regprogdanual->mesp_08,0)}}</td>
                                    <td style="text-align:left; vertical-align: middle;font-size:10px;">{{number_format($regprogdanual->mesp_09,0)}}</td>
                                    <td style="text-align:left; vertical-align: middle;font-size:10px;">{{number_format($regprogdanual->mesp_10,0)}}</td>
                                    <td style="text-align:left; vertical-align: middle;font-size:10px;">{{number_format($regprogdanual->mesp_11,0)}}</td>
                                    <td style="text-align:left; vertical-align: middle;font-size:10px;">{{number_format($regprogdanual->mesp_12,0)}}</td>                                        
                                </tr>

                                <tr>
                                    <td style="color:#1a1c50;text-align:left; vertical-align: middle;font-size:9px;"></td>
                                    <td style="color:#1a1c50;text-align:left; vertical-align: middle;font-size:9px;"></td>
                                    <td style="color:#1a1c50;text-align:left; vertical-align: middle;font-size:9px;"></td>
                                    <td style="color:#1a1c50;text-align:left; vertical-align: middle;font-size:9px;">Avances</td>
                                    <td style="color:#1a1c50;text-align:left; vertical-align: middle;font-size:9px;"></td>                                                                            
                                    <td style="color:#1a1c50;text-align:left; vertical-align: middle;font-size:10px;">{{number_format($regprogdanual->mesc_01,0)}}</td>
                                    <td style="color:#1a1c50;text-align:left; vertical-align: middle;font-size:10px;">{{number_format($regprogdanual->mesc_02,0)}}</td>
                                    <td style="color:#1a1c50;text-align:left; vertical-align: middle;font-size:10px;">{{number_format($regprogdanual->mesc_03,0)}}</td>
                                    <td style="color:#1a1c50;text-align:left; vertical-align: middle;font-size:10px;">{{number_format($regprogdanual->mesc_04,0)}}</td>
                                    <td style="color:#1a1c50;text-align:left; vertical-align: middle;font-size:10px;">{{number_format($regprogdanual->mesc_05,0)}}</td>
                                    <td style="color:#1a1c50;text-align:left; vertical-align: middle;font-size:10px;">{{number_format($regprogdanual->mesc_06,0)}}</td>
                                    <td style="color:#1a1c50;text-align:left; vertical-align: middle;font-size:10px;">{{number_format($regprogdanual->mesc_07,0)}}</td>
                                    <td style="color:#1a1c50;text-align:left; vertical-align: middle;font-size:10px;">{{number_format($regprogdanual->mesc_08,0)}}</td>
                                    <td style="color:#1a1c50;text-align:left; vertical-align: middle;font-size:10px;">{{number_format($regprogdanual->mesc_09,0)}}</td>
                                    <td style="color:#1a1c50;text-align:left; vertical-align: middle;font-size:10px;">{{number_format($regprogdanual->mesc_10,0)}}</td>
                                    <td style="color:#1a1c50;text-align:left; vertical-align: middle;font-size:10px;">{{number_format($regprogdanual->mesc_11,0)}}</td>
                                    <td style="color:#1a1c50;text-align:left; vertical-align: middle;font-size:10px;">{{number_format($regprogdanual->mesc_12,0)}}</td>
                                </tr>                                    
                                <tr>
                                    <td style="color:purple;text-align:left; vertical-align: middle;font-size:9px;"></td>
                                    <td style="color:purple;text-align:left; vertical-align: middle;font-size:9px;"></td>
                                    <td style="color:purple;text-align:left; vertical-align: middle;font-size:9px;"></td>
                                    <td style="color:purple;text-align:left; vertical-align: middle;font-size:9px;">%</td>
                                    <td style="color:purple;text-align:left; vertical-align: middle;font-size:9px;"></td>                                                                            
                                    <td style="color:purple;text-align:left; vertical-align: middle;font-size:10px;">{{number_format($regprogdanual->mes_p01,2)}}</td>
                                    <td style="color:purple;text-align:left; vertical-align: middle;font-size:10px;">{{number_format($regprogdanual->mes_p02,2)}}</td>
                                    <td style="color:purple;text-align:left; vertical-align: middle;font-size:10px;">{{number_format($regprogdanual->mes_p03,2)}}</td>
                                    <td style="color:purple;text-align:left; vertical-align: middle;font-size:10px;">{{number_format($regprogdanual->mes_p04,2)}}</td>
                                    <td style="color:purple;text-align:left; vertical-align: middle;font-size:10px;">{{number_format($regprogdanual->mes_p05,2)}}</td>
                                    <td style="color:purple;text-align:left; vertical-align: middle;font-size:10px;">{{number_format($regprogdanual->mes_p06,2)}}</td>
                                    <td style="color:purple;text-align:left; vertical-align: middle;font-size:10px;">{{number_format($regprogdanual->mes_p07,2)}}</td>
                                    <td style="color:purple;text-align:left; vertical-align: middle;font-size:10px;">{{number_format($regprogdanual->mes_p08,2)}}</td>
                                    <td style="color:purple;text-align:left; vertical-align: middle;font-size:10px;">{{number_format($regprogdanual->mes_p09,2)}}</td>
                                    <td style="color:purple;text-align:left; vertical-align: middle;font-size:10px;">{{number_format($regprogdanual->mes_p10,2)}}</td>
                                    <td style="color:purple;text-align:left; vertical-align: middle;font-size:10px;">{{number_format($regprogdanual->mes_p11,2)}}</td>
                                    <td style="color:purple;text-align:left; vertical-align: middle;font-size:10px;">{{number_format($regprogdanual->mes_p12,2)}}</td>
                                </tr>     
                                    <tr>
                                        <td style="color:purple;text-align:left; vertical-align: middle;font-size:9px;"></td>
                                        <td style="color:purple;text-align:left; vertical-align: middle;font-size:9px;"></td>
                                        <td style="color:purple;text-align:left; vertical-align: middle;font-size:9px;"></td>
                                        <td style="color:purple;text-align:left; vertical-align: middle;font-size:9px;">Semáforos</td>
                                        <td style="color:purple;text-align:left; vertical-align: middle;font-size:9px;"></td>   
                                        <td style="text-align:center;">  
                                        @switch($regprogdanual->semaf_01)
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
                                        @switch($regprogdanual->semaf_02)
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
                                        @switch($regprogdanual->semaf_03)
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
                                        @switch($regprogdanual->semaf_04)
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
                                        @switch($regprogdanual->semaf_05)
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
                                        @switch($regprogdanual->semaf_06)
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
                                        @switch($regprogdanual->semaf_07)
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
                                        @switch($regprogdanual->semaf_08)
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
                                        @switch($regprogdanual->semaf_09)
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
                                        @switch($regprogdanual->semaf_10)
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
                                        @switch($regprogdanual->semaf_11)
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
                                        @switch($regprogdanual->semaf_12)
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
                                        <td style="text-align:center;"></td>
                                    </tr>                                                                    
                            </table>

                            <div class="row">    
                                <div class="col-xs-4 form-group">
                                    <label >Mes a reportar </label>
                                    <select class="form-control m-bot15" name="mes_id2" id="mes_id2" required>
                                        <option selected="true" disabled="disabled">Seleccionar mes </option>
                                        @foreach($regmeses as $mes)
                                            <option value="{{$mes->mes_id}}">{{$mes->mes_desc}}</option>
                                        @endforeach
                                    </select>                                    
                                </div> 
                            </div>

                            <div class="row">
                                <div class="col-xs-2 form-group">
                                    <label >Avance a reportar </label>
                                    <input type="number" min="0" max="999999999999" class="form-control" name="avance" id="avance" placeholder="Avance " value="0" required>
                                </div> 
                            </div>
                                            
                             <div class="row">                                
                                <div class="col-xs-12 form-group">
                                    <label >Observaciones (4,000 caracteres)</label>
                                     <textarea class="form-control" name="obs_02" id="obs_02" rows="3" cols="120" placeholder="Observaciones (4,000 caracteres)" required>
                                    </textarea>
                                </div>                                
                            </div>

                            <div class="row">
                                @if(count($errors) > 0)
                                    <div class="alert alert-danger" role="alert">
                                        <ul>
                                            @foreach($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <div class="col-md-12 offset-md-5">
                                    {!! Form::submit('Registrar',['class' => 'btn btn-success btn-flat pull-right']) !!}
                                    
                                    @foreach($regprogeanual as $encpa)
                                       <a href="{{route('verInformelab',$encpa->folio)}}" role="button" id="cancelar" class="btn btn-danger">Cancelar
                                       </a>
                                       @break
                                    @endforeach                                          
                                </div>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('request')
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\informelabRequest','#actualizarInformelab') !!}
@endsection

@section('javascrpt')
<script>
    function soloNumeros(e){
       key = e.keyCode || e.which;
       tecla = String.fromCharCode(key);
       letras = "1234567890";
       especiales = "8-37-39-46";
       tecla_especial = false
       for(var i in especiales){
            if(key == especiales[i]){
                tecla_especial = true;
                break;
            }
        }
        if(letras.indexOf(tecla)==-1 && !tecla_especial){
            return false;
        }
    }    

    function soloAlfa(e){
       key = e.keyCode || e.which;
       tecla = String.fromCharCode(key);
       letras = "abcdefghijklmnñopqrstuvwxyz ABCDEFGHIJKLMNÑOPQRSTUVWXYZ.";
       especiales = "8-37-39-46";

       tecla_especial = false
       for(var i in especiales){
            if(key == especiales[i]){
                tecla_especial = true;
                break;
            }
        }
        if(letras.indexOf(tecla)==-1 && !tecla_especial){
            return false;
        }
    }

    function soloLetras(e){
       key = e.keyCode || e.which;
       tecla = String.fromCharCode(key);
       letras = "abcdefghijklmnñopqrstuvwxyz ABCDEFGHIJKLMNÑOPQRSTUVWXYZ";
       especiales = "8-37-39-46";

       tecla_especial = false
       for(var i in especiales){
            if(key == especiales[i]){
                tecla_especial = true;
                break;
            }
        }
        if(letras.indexOf(tecla)==-1 && !tecla_especial){
            return false;
        }
    }
    function general(e){
       key = e.keyCode || e.which;
       tecla = String.fromCharCode(key);
       letras = "abcdefghijklmnñopqrstuvwxyz ABCDEFGHIJKLMNÑOPQRSTUVWXYZ1234567890,.;:-_<>!%()=?¡¿/*+";
       especiales = "8-37-39-46";

       tecla_especial = false
       for(var i in especiales){
            if(key == especiales[i]){
                tecla_especial = true;
                break;
            }
        }
        if(letras.indexOf(tecla)==-1 && !tecla_especial){
            return false;
        }
    }
    function soloAlfaSE(e){
       key = e.keyCode || e.which;
       tecla = String.fromCharCode(key);
       letras = "abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZ0123456789";
       especiales = "8-37-39-46";

       tecla_especial = false
       for(var i in especiales){
            if(key == especiales[i]){
                tecla_especial = true;
                break;
            }
        }
        if(letras.indexOf(tecla)==-1 && !tecla_especial){
            return false;
        }
    }    
</script>

<script>
    $('.datepicker').datepicker({
        format: "dd/mm/yyyy",
        startDate: '-29y',
        endDate: '-18y',
        startView: 2,
        maxViewMode: 2,
        clearBtn: true,        
        language: "es",
        autoclose: true
    });
</script>
@endsection

