@extends('sicinar.principal')

@section('title','Ver soportes documentales del programa de trabajo')

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
            <h1>Registro - Soportes
                <small> Seleccionar para editar registro de soporte </small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Menú</a></li>
                <li><a href="#">Registro - </a></li>   
                <li><a href="#">Soportes     </a></li>               
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">

                        <div class="page-header" style="text-align:right;">
                             
                            {{ Form::open(['route' => 'buscarsoporte', 'method' => 'GET', 'class' => 'form-inline pull-right']) }}
                                <div class="form-group">
                                    {{ Form::text('nameiap', null, ['class' => 'form-control', 'placeholder' => '...']) }}
                                </div>                                             
                                <div class="form-group">
                                    {{ Form::text('folio', null, ['class' => 'form-control', 'placeholder' => 'Folio']) }}
                                </div>
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
                                        <th style="text-align:left; vertical-align: middle;font-size:10px;">Periodo        </th>
                                        <th style="text-align:left; vertical-align: middle;font-size:10px;">Folio          </th>
                                        <th style="text-align:left; vertical-align: middle;font-size:10px;">Programa       </th>
                                        <th style="text-align:left; vertical-align: middle;font-size:10px;">Proyecto       </th>                                        
                                        <th style="text-align:left; vertical-align: middle;font-size:10px;">Unidad <br>Responsable</th>
                                        <th style="text-align:left; vertical-align: middle;font-size:10px;">Unidad <br>Ejecutora  </th>
                                        <th style="text-align:left; vertical-align: middle;font-size:10px;">Elaboró        </th>
                                        <th style="text-align:left; vertical-align: middle;font-size:10px;">Revisó         </th>
                                        <th style="text-align:left; vertical-align: middle;font-size:10px;">Autorizó       </th>
                                        
                                        <th style="text-align:left; vertical-align: middle;font-size:10px;">Fecha<br>Entrega </th>
                                        <th style="text-align:left; vertical-align: middle;font-size:10px;">Fecha<br>Reg.    </th>
                                        <th style="text-align:left; vertical-align: middle;font-size:10px;">Tot.<br>Act's    </th>
                                        <th style="text-align:center;vertical-align: middle;font-size:10px;width:100px;">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($regprogeanual as $epa)
                                    <tr>
                                        <td style="text-align:left; vertical-align: middle;font-size:09px;">{{$epa->periodo_id}}</td>
                                        <td style="text-align:left; vertical-align: middle;font-size:09px;">{{$epa->folio}}     </td>  
                                        <td style="text-align:left; vertical-align: middle;font-size:09px;"> 
                                            {{$epa->epprog_id}}
                                            @foreach($regepprog as $prog)
                                                @if($prog->epprog_id == $epa->epprog_id)
                                                    {{$prog->epprog_desc}}
                                                    @break 
                                                @endif
                                            @endforeach
                                        </td>                                                            
                                        <td style="text-align:left; vertical-align: middle;font-size:09px;"> 
                                            {{$epa->epproy_id}}
                                            @foreach($regepproy as $proy)
                                                @if($proy->epproy_id == $epa->epproy_id)
                                                    {{$proy->epproy_desc}}
                                                    @break 
                                                @endif
                                            @endforeach
                                        </td>                                              
                                        <td style="text-align:left; vertical-align: middle;font-size:09px;"> 
                                            {{$epa->depen_id1}} 
                                            @foreach($regdepen as $depen)
                                                @if($depen->depen_id == $epa->depen_id1)
                                                    {{$depen->depen_desc}}
                                                    @break 
                                                @endif
                                            @endforeach
                                        </td>                                                    
                                        <td style="text-align:left; vertical-align: middle;font-size:09px;"> 
                                            {{$epa->depen_id2}} 
                                            @foreach($regdepen as $depen)
                                                @if($depen->depen_id == $epa->depen_id2)
                                                    {{$depen->depen_desc}}
                                                    @break 
                                                @endif
                                            @endforeach
                                        </td>                                                                          
                                        <td style="text-align:left; vertical-align: middle;font-size:09px;">
                                            {{Trim($epa->responsable)}}
                                        </td>
                                        <td style="text-align:left; vertical-align: middle;font-size:09px;">
                                            {{Trim($epa->elaboro)}}    
                                        </td>
                                        <td style="text-align:left; vertical-align: middle;font-size:09px;">
                                            {{Trim($epa->autorizo)}}    
                                        </td>
                                        <td style="text-align:left; vertical-align: middle;font-size:09px;">{{$epa->fecha_entrega2}}</td>
                                        <td style="text-align:left; vertical-align: middle;font-size:09px;">{{date("d/m/Y",strtotime($epa->fecreg))}}</td>
                                        <td style="text-align:center; vertical-align: middle;font-size:09px;"><small>
                                            @foreach($totactivs as $partida)
                                                @if($partida->periodo_id == $epa->periodo_id && $partida->folio == $epa->folio)
                                                    @if(!empty($partida->totactividades)&&(!is_null($partida->totactividades)))
                                                       <b style="color:darkgreen;">{{$partida->totactividades}}</b>
                                                    @else
                                                       <b style="color:darkred;">0 Faltan las actividades</b>
                                                    @endif
                                                    @break
                                                @endif
                                            @endforeach </small>
                                        </td>                                                                                 

                                        <td style="text-align:center;">
                                            <a href="{{route('versoportesdet',$epa->folio)}}" class="btn btn-primary btn_xs" title="Ver soportes del programa anual"><i class="fa fa-file-new-o"></i><span class="glyphicon glyphicon-plus"></span><small>Soportes</small>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {!! $regprogeanual->appends(request()->input())->links() !!}
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
