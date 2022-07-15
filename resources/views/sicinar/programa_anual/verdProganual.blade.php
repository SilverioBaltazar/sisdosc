@extends('sicinar.principal')

@section('title','Ver accciones o metas del programa anual')

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
            <h1>Registro
                <small> Seleccionar para editar acción o meta del Programa anual </small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Menú</a></li>
                <li><a href="#">Registro - </a></li>   
                <li><a href="#">Programa anual (acciones o metas)  </a></li>               
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">

                        <table id="tabla1" class="table table-hover table-striped">
                        @foreach($regprogeanual as $encpa)
                            <tr>
                                <td style="text-align:left; vertical-align: middle;"> </td>                            
                                <td style="text-align:right; vertical-align: middle;"> 
                                    <b></b>
                                    <b>
                                    <a href="{{route('verpa')}}" role="button" id="cancelar" class="btn btn-success"><small>Regresar a programa anual</small>
                                    </a>
                                    <a href="{{route('nuevodpa',$encpa->folio)}}" class="btn btn-primary btn_xs" title="Nueva actividad del programa de trabajo"><i class="fa fa-file-new-o"></i><span class="glyphicon glyphicon-plus"></span><small>Nueva acción o meta </small>
                                    </a>
                                    </b>
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
                                &nbsp;&nbsp;{{$encpa->depen_id1}}&nbsp;&nbsp;
                                @foreach($regdepen as $depen)
                                    @if($depen->depen_id == $encpa->depen_id1)
                                        {{Trim($depen->depen_desc)}}
                                        @break
                                    @endif
                                @endforeach
                                </b><br>                    
                                Unidad ejecutora: <b style="text-align:left; vertical-align: middle; color:green;font-size:10px;">
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$encpa->depen_id2}}&nbsp;&nbsp;
                                @foreach($regdepen as $depen)
                                    @if($depen->depen_id == $encpa->depen_id2)
                                        {{Trim($depen->depen_desc)}}
                                        @break
                                    @endif
                                @endforeach
                                </b>              
                                </td>

                                <td style="border:0; text-align:right;font-size:10px;">
                                Fecha de entrega:<b style="text-align:right; vertical-align: middle; color:green;font-size:10px;">
                                {{$encpa->fecha_entrega2}}
                                </b><br>
                                Periodo fiscal:<b style="text-align:right; vertical-align: middle; color:green;font-size:10px;">
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                {{$encpa->periodo_id}}</b><br>
                                Folio:<b style="text-align:right; vertical-align: middle; color:green;font-size:10px;">
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                {{$encpa->folio}}</b><br>
                                <b></b>
                                </td>
                            </tr>
                            @endforeach             
                        </table>

                        <div class="box-body">
                            <table id="tabla1" class="table table-hover table-striped">
                                <thead style="color: brown;" class="justify">
                                    <tr>
                                        <th style="text-align:left;   vertical-align: middle;">#                </th>
                                        <th style="text-align:left;   vertical-align: middle;">No  <br>IGOB     </th>
                                        <th style="text-align:left;   vertical-align: middle;">SPP              </th>                                                                                
                                        <th style="text-align:left;   vertical-align: middle;">Acción o meta    </th>
                                        <th style="text-align:left;   vertical-align: middle;">Unidad<br>Medida </th>
                                        <th style="text-align:left;   vertical-align: middle;">Meta<br>Anual    </th>
                                        <th style="text-align:left;   vertical-align: middle;">Ene              </th>
                                        <th style="text-align:left;   vertical-align: middle;">Feb              </th>
                                        <th style="text-align:left;   vertical-align: middle;">Mar              </th> 
                                        <th style="text-align:left;   vertical-align: middle;">Abr              </th>
                                        <th style="text-align:left;   vertical-align: middle;">May              </th>
                                        <th style="text-align:left;   vertical-align: middle;">Jun              </th>
                                        <th style="text-align:left;   vertical-align: middle;">Jul              </th>
                                        <th style="text-align:left;   vertical-align: middle;">Ago              </th>
                                        <th style="text-align:left;   vertical-align: middle;">Sep              </th>
                                        <th style="text-align:left;   vertical-align: middle;">Oct              </th>
                                        <th style="text-align:left;   vertical-align: middle;">Nov              </th>
                                        <th style="text-align:left;   vertical-align: middle;">Dic              </th>
                                        
                                        <th style="text-align:center; vertical-align: middle; width:100px;">Acciones</th>

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
                                        <td style="text-align:left; vertical-align: middle;font-size:10px;">{{$dpa->totp_01}}</td>   
                                        <td style="text-align:left; vertical-align: middle;font-size:10px;">{{$dpa->mesp_01}}</td>
                                        <td style="text-align:left; vertical-align: middle;font-size:10px;">{{$dpa->mesp_02}}</td>
                                        <td style="text-align:left; vertical-align: middle;font-size:10px;">{{$dpa->mesp_03}}</td>
                                        <td style="text-align:left; vertical-align: middle;font-size:10px;">{{$dpa->mesp_04}}</td>
                                        <td style="text-align:left; vertical-align: middle;font-size:10px;">{{$dpa->mesp_05}}</td>
                                        <td style="text-align:left; vertical-align: middle;font-size:10px;">{{$dpa->mesp_06}}</td>
                                        <td style="text-align:left; vertical-align: middle;font-size:10px;">{{$dpa->mesp_07}}</td>
                                        <td style="text-align:left; vertical-align: middle;font-size:10px;">{{$dpa->mesp_08}}</td>
                                        <td style="text-align:left; vertical-align: middle;font-size:10px;">{{$dpa->mesp_09}}</td>
                                        <td style="text-align:left; vertical-align: middle;font-size:10px;">{{$dpa->mesp_10}}</td>
                                        <td style="text-align:left; vertical-align: middle;font-size:10px;">{{$dpa->mesp_11}}</td>
                                        <td style="text-align:left; vertical-align: middle;font-size:10px;">{{$dpa->mesp_12}}</td>
                                        
                                        <td style="text-align:center;">
                                            <a href="{{route('editardpa',array($dpa->folio,$dpa->partida) )}}" class="btn badge-warning" title="Editar acción o meta del programa anual"><i class="fa fa-edit"></i>
                                            </a>
                                            <a href="{{route('borrardpa',array($dpa->folio,$dpa->partida) )}}" class="btn badge-danger" title="Borrar acción o meta del programa anual" onclick="return confirm('¿Seguro que desea borrar acción o meta del programa anual?')"><i class="fa fa-times"></i>
                                            </a>
                                        </td>
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

