@extends('sicinar.principal')

@section('title','Ver visitas de verificación')

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
            <h1>Visitas de diligencia
                <small> Seleccionar para editar o nueva visita de diligencia</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Menú</a></li>
                <li><a href="#">Agenda de diligencias </a></li>
                <li><a href="#">Visitas   </a></li>         
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">

                        <div class="box-header" style="text-align:right;">
                            
                            {{ Form::open(['route' => 'buscarVisita', 'method' => 'GET', 'class' => 'form-inline pull-right']) }}
                                <div class="form-group"> Periodo
                                    <!--{{ Form::text('fper', null, ['class' => 'form-control', 'placeholder' => 'Periodo','maxlength' => '10']) }}
                                    {!! Form::label('fper','IAP') !!} -->
                                    <!--<option value=""> --Seleccionar periodo-- </option> -->
                                    <select class="form-control m-bot15" name="fper" id="fper" class="form-control">
                                        <option value=""> </option> 
                                        @foreach($regperiodos as $periodo)
                                            <option value="{{$periodo->periodo_id}}">{{trim($periodo->periodo_desc)}}</option>
                                        @endforeach   
                                    </select>
                                </div>
                                <div class="form-group">Mes
                                    <!--{{ Form::text('fmes', null, ['class' => 'form-control', 'placeholder' => 'Mes','maxlength' => '10']) }}  -->
                                    <!--<option value=""> --Seleccionar periodo-- </option> -->
                                    <select class="form-control m-bot15" name="fmes" id="fmes" class="form-control">
                                        <option value=""> </option> 
                                        @foreach($regmeses as $mes)
                                            <option value="{{$mes->mes_id}}">{{trim($mes->mes_desc)}}</option>
                                        @endforeach   
                                    </select>
                                </div>                                
                                <div class="form-group">OSC
                                    <!--{{ Form::text('fosc', null, ['class' => 'form-control', 'placeholder' => 'OSC','maxlength' => '10']) }}-->
                                    <select class="form-control m-bot15" name="fiap" id="fiap" class="form-control">
                                        <option value=""> </option>
                                        @foreach($regosc as $osc)
                                            <option value="{{$osc->osc_id}}">{{substr($osc->osc_desc,1,20)}}</option>
                                        @endforeach   
                                    </select>
                                </div>
                                <!--
                                <div class="form-group">
                                    {{ Form::text('bio', null, ['class' => 'form-control', 'placeholder' => 'Concepto']) }}
                                </div>
                                -->
                                <div class="form-group">
                                    <button type="submit" class="btn btn-default">
                                    <span class="glyphicon glyphicon-search"></span>
                                    </button>
                                </div>
                            {{ Form::close() }}
                        </div>

                        <div class="box-body">
                            <table id="tabla1" class="table table-hover table-striped">
                                <thead style="color: brown;" class="justify">
                                    <tr>
                                        <th style="text-align:center; vertical-align: middle;">Folio<br>Sistema </th>
                                        <th style="text-align:center; vertical-align: middle;">Oficio    </th>
                                        <th style="text-align:center; vertical-align: middle;">Año       </th>
                                        <th style="text-align:center; vertical-align: middle;">Mes       </th>
                                        <th style="text-align:center; vertical-align: middle;">Dia       </th>
                                        <th style="text-align:center; vertical-align: middle;">Hora      </th>
                                        <th style="text-align:left;   vertical-align: middle;">OSC       </th>
                                        <th style="text-align:left;   vertical-align: middle;">Domicilio </th>
                                        <th style="text-align:left;   vertical-align: middle;">Objetivo  </th>                                        
                                        <th style="text-align:center; vertical-align: middle;">Estado    </th>
                                        
                                        <th style="text-align:center; vertical-align: middle; width:100px;">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($regvisita as $visita)
                                    <tr>
                                        <td style="font-size:10px; text-align:left; vertical-align: middle;">{{$visita->visita_folio}}</td>  
                                        <td style="font-size:10px; text-align:left; vertical-align: middle;">{{$visita->visita_oficio}}</td>                                      
                                        <td style="font-size:10px; text-align:left; vertical-align: middle;">{{$visita->periodo_id}}
                                        </td> 
                                        <td style="font-size:10px; text-align:left; vertical-align: middle;">  
                                            @foreach($regmeses as $mes)
                                                @if($mes->mes_id == $visita->mes_id)
                                                    {{$mes->mes_desc}}
                                                    @break
                                                @endif
                                            @endforeach 
                                        </td>                    
                                        <td style="font-size:10px; text-align:center; vertical-align: middle;">
                                            @foreach($regdias as $dia)
                                                @if($dia->dia_id == $visita->dia_id)
                                                    {{$dia->dia_desc}}
                                                    @break
                                                @endif
                                            @endforeach 
                                        </td>
                                        <td style="font-size:10px; text-align:center; vertical-align: middle;">
                                            @foreach($reghoras as $hora)
                                                @if($hora->hora_id == $visita->hora_id)
                                                    {{$hora->hora_desc}}
                                                    @break
                                                @endif
                                            @endforeach 
                                        </td>
                                        
                                        <td style="font-size:10px; text-align:left; vertical-align: middle;">
                                            @foreach($regosc as $osc)
                                                @if($osc->osc_id == $visita->osc_id)
                                                    {{$osc->osc_desc}}
                                                    @break
                                                @endif
                                            @endforeach
                                        </td>                                        
                                        <td style="font-size:10px; text-align:left; vertical-align: middle;">{{Trim($visita->visita_dom)}}</td>

                                        <td style="font-size:10px; text-align:left; vertical-align: middle;">
                                        {{Trim($visita->visita_obj).' '.Trim($visita->visita_obs3)}}
                                        </td>
                                        @switch($visita->visita_edo) 
                                        @case(0)  <!-- amarillo -->
                                            <td style="text-align:center;">
                                                 <img src="{{ asset('images/semaforo_amarillo.jpg') }}" width="15px" height="15px" title="En proceso" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                            </td>
                                            @break
                                        @case(1)  <!-- cerrada -->
                                            <td style="text-align:center;">
                                                <img src="{{ asset('images/semaforo_verde.jpg') }}" width="15px" height="15px" title="Cerrada" style="text-align:center;margin-right: 15px;vertical-align: middle;"/>    
                                            </td>
                                            @break
                                        @case(2)
                                            <td style="text-align:center;">
                                                <img src="{{ asset('images/semaforo_rojo.jpg') }}" width="15px" height="15px" title="Cancelada" style="text-align:center;margin-right: 15px;vertical-align: middle;"/>
                                            </td>
                                            @break 
                                        @default 
                                            <td style="text-align:center;"> 
                                                <img src="{{ asset('images/semaforo_rojo.jpg') }}" width="15px" height="15px" title="Cancelada" style="text-align:center;margin-right: 15px;vertical-align: middle;"/> 
                                            </td>                                          
                                        @endswitch
                                        
                                        <td style="text-align:center;">
                                            <a href="{{route('editarVisita',$visita->visita_folio)}}" class="btn badge-warning" title="Registrar visita de diligencia"><i class="fa fa-edit"></i>
                                            </a>
                                            <a href="{{route('borrarVisita',$visita->visita_folio)}}" class="btn badge-danger" title="Borrar registro de visita de la agenda" onclick="return confirm('¿Seguro que desea borrar el registro de visita de la agenda de diligencias?')"><i class="fa fa-times"></i>
                                            </a>
 
                                            <a href="{{route('actavisitaPDF',$visita->visita_folio)}}" class="btn btn-danger" title="Generar Acta de visita de verificación en formato PDF"><i class="fa fa-file-pdf-o"></i><small>PDF</small></a>  
                                        </td>                          
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {!! $regvisita->appends(request()->input())->links() !!}
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