@extends('sicinar.principal')

@section('title','Ver solicitud de constancia')

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
            <h1><i class="fa fa-dashboard"></i>&nbsp;&nbsp;Menú.
                <small>5. Solicitud de constancia  </small>
                <small> - Seleccionar para editar </small>
            </h1>
            <ol class="breadcrumb">
                <li><img src="{{ asset('images/b5.jpg') }}" border="0" width="30" height="30">&nbsp;&nbsp;Solicitud de constancia</li> 
            </ol>            
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box"> 
                        <p align="justify"><b style="color:red;">
                            Instrucciones:</b> <b style="color:green;">
                            Deberá subir en este apartado el archivo digital de Oficio de Solicitud de Cumplimiento del Objeto Social una vez cumplidos y actualizados los requisitos previos del punto 1 al 4.
                            Se requiere escanear a una resolución de 300 ppp en blanco y negro.
                            </b>
                        </p>                    
                        <div class="box-header" style="text-align:right;">
                            {{ Form::open(['route' => 'buscarConst', 'method' => 'GET', 'class' => 'form-inline pull-right']) }}
                                <div class="form-group">
                                    {{ Form::text('fper', null, ['class' => 'form-control', 'placeholder' => 'Periodo fiscal']) }}
                                </div>
                                <div class="form-group">
                                    {{ Form::text('nameiap', null, ['class' => 'form-control', 'placeholder' => 'Nombre OSC']) }}
                                </div>                                           
                                <div class="form-group"> 
                                    <button type="submit" class="btn btn-default">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </button>
                                </div>
                                <div class="form-group">
                                    <a href="{{route('nuevaConst')}}" class="btn btn-primary btn_xs" title="Registrar Sol. Constancia"><i class="fa fa-file-new-o"></i><span class="glyphicon glyphicon-plus"></span>Registrar Solicitud de Constancia</a>
                                </div>                                
                            {{ Form::close() }}                            
                        </div>                        
                        <div class="box-body">
                            <table id="tabla1" class="table table-striped table-bordered table-sm">
                                <thead style="color: brown;" class="justify">
                                    <tr>
                                        <th style="text-align:left;   vertical-align: middle;font-size:11px;">Periodo            </th>
                                        <th style="text-align:left;   vertical-align: middle;font-size:11px;">Folio         </th>  
                                        <th style="text-align:left;   vertical-align: middle;font-size:11px;">OSC             </th>
                                        <th style="text-align:center; vertical-align: middle;font-size:11px; width:100px;">Acciones</th>
                                    </tr>
                                </thead>
 
                                <tbody>
                                    @foreach($regconstancia as $Constancia)
                                    <tr>
                                        <td style="text-align:left; vertical-align: middle;font-size:11px;">{{$Constancia->periodo_id}}</td>
                                        <td style="text-align:left; vertical-align: middle;font-size:11px;">{{$Constancia->osc_folio}} </td>
                                        <td style="text-align:left; vertical-align: middle;font-size:11px;">
                                            @foreach($regosc as $osc)
                                                @if($osc->osc_id == $Constancia->osc_id)
                                                    {{$osc->osc_id.' '.Trim($osc->osc_desc)}} 
                                                    @break
                                                @endif
                                            @endforeach    
                                        </td>  
                                        @if(!empty($Constancia->osc_d1)&&(!is_null($Constancia->osc_d1)))
                                            <td style="color:darkgreen;text-align:center; vertical-align:middle;font-size:11px;" title="Solicitud de constancia de cumplimiento de objeto social">
                                                <a href="/images/{{$Constancia->osc_d1}}" class="btn btn-danger" title="Solicitud de constancia de cumplimiento de objeto social"><i class="fa fa-file-pdf-o"></i>
                                                </a>
                                                <a href="{{route('editarConst1',$Constancia->osc_folio)}}" class="btn badge-warning" title="Editar Solicitud de constancia de cumplimiento de objeto social"><i class="fa fa-edit"></i>
                                                </a>
                                            </td>
                                        @else
                                            <td style="color:darkred; text-align:center; vertical-align: middle;font-size:11px;" title="Solicitud de constancia de cumplimiento de objeto social"><i class="fa fa-times"></i>
                                                <a href="{{route('editarConst1',$Constancia->osc_folio)}}" class="btn badge-warning" title="Editar Solicitud de constancia de cumplimiento de objeto social"><i class="fa fa-edit"></i>
                                                </a>
                                            </td>   
                                        @endif

                                        <td style="text-align:center; vertical-align: middle;font-size:11px;">
                                            <a href="{{route('editarConst',$Constancia->osc_folio)}}" class="btn badge-warning" title="Editar Solicitud de constancia de cumplimiento de objeto social"><i class="fa fa-edit"></i>
                                            </a>
                                            @if(session()->get('rango') == '4')
                                                <a href="{{route('borrarConst',$Constancia->osc_folio)}}" class="btn badge-danger" title="Borrar registro" onclick="return confirm('¿Seguro que desea borrar Solicitud de constancia de cumplimiento de objeto social?')"><i class="fa fa-times"></i>
                                                </a>                                             
                                            @endif 
                                        </td>
                                    </tr>   
                                        @if(session()->get('rango') == '0')
                                            <tr>
                                                <td style="text-align:left; rowspan=2;">
                                                    <a href="{{route('verReqc')}}">                                        
                                                    <img src="{{ asset('images/b4.jpg') }}"   border="0" width="25" height="30" title="Requisitos admon.">
                                                    <img src="{{ asset('images/fant.jpg') }}" border="0" width="30" height="30" title="Anterior requisito">
                                                    </a>                                            
                                                </td>
                                                <td style="text-align:center;"></td>                                            
                                                <td style="text-align:right; ">
                                                       <a href="{{route('verOsc5')}}">                                        
                                                       <img src="{{ asset('images/fsig.jpg') }}" border="0" width="30" height="30" title="Inicio">
                                                       <img src="{{ asset('images/b1.jpg') }}"   border="0" width="25" height="30" title="Registro OSC">
                                                       </a>                                                
                                                </td>
                                            </tr>                                    
                                            <tr>
                                                <td colspan="9">
                                                <p align="justify"><b style="color:red;">
                                                Aviso importante:</b> <b style="color:green;">
                                                Los datos registrados y los archivos digitales subidos al sistema; pasarán a un proceso de validación al área correspondiente 
                                                para continuar el trámite y en un máximo de 10 días hábiles personal de la dependencia se comunicarán para informar el estado del 
                                                trámite. 
                                                
                                                Para dudas enviar su solicitud al correo electrónico: registro.social.edomex@gmail.com
                                                </b></p>
                                                </td>
                                            </tr>
                                        @endif

                                    @endforeach
                                </tbody>
                            </table>
                            {!! $regconstancia->appends(request()->input())->links() !!}
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
