@extends('sicinar.principal')

@section('title','Editar solicitud de constancia')

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
                <small> - Editar </small>
            </h1>
            <ol class="breadcrumb">
                <li><img src="{{ asset('images/b5.jpg') }}" border="0" width="30" height="30">&nbsp;&nbsp;Solicitud de constancia - Editar</li> 
            </ol>            
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-success">
                        <p align="justify"><b style="color:red;">
                            Instrucciones:</b> <b style="color:green;">
                            Deberá subir en este apartado el archivo digital de Oficio de Solicitud de Cumplimiento del Objeto Social una vez cumplidos y actualizados los requisitos previos del punto 1 al 4.
                            Se requiere scanear a una resolución de 300 ppp en blanco y negro.
                            </b>
                        </p>                    
                        {!! Form::open(['route' => ['actualizarConst1',$regconstancia->osc_folio], 'method' => 'PUT', 'id' => 'actualizarConst1', 'enctype' => 'multipart/form-data']) !!}
                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-2 form-group">
                                    <input type="hidden" name="periodo_id" id="periodo_id" value="{{$regconstancia->periodo_id}}">                                
                                    <label>Periodo fiscal </label><br>
                                        <label label style="color:green;">{{$regconstancia->periodo_id}}</label>
                                </div>                                   
                                <div class="col-xs-8 form-group">
                                    <input type="hidden" name="osc_id" id="osc_id" value="{{$regconstancia->osc_id}}">
                                    <label>OSC &nbsp;&nbsp;&nbsp; </label><br>
                                    @foreach($regosc as $osc)
                                        @if($osc->osc_id == $regconstancia->osc_id)
                                            <label style="color:green;">{{$regconstancia->osc_id.' '.$osc->osc_desc}}</label>
                                            @break
                                        @endif
                                    @endforeach
                                </div>  
                                <div class="col-xs-1 form-group">
                                    <input type="hidden" name="osc_folio" id="osc_folio" value="{{$regconstancia->osc_folio}}">
                                    <label>Folio </label><br><label style="color:green;">{{$regconstancia->osc_folio}}</label>
                                </div>                                                                                                                            
                            </div>

                            <div class="row">
                                <div class="col-xs-12 form-group">
                                    <label style="background-color:yellow;color:red"><b>Nota importante:</b>&nbsp;&nbsp;El archivo digital, NO deberá ser mayor a 1,500 kBytes en tamaño.  </label>
                                </div>   
                            </div>  
                            @if (!empty($regconstancia->osc_d1)||!is_null($regconstancia->osc_d1)) 
                                <div class="row">               
                                    <div class="col-xs-6 form-group">
                                        <label >Archivo de digital de Solicitud de Constancia de Renovación de Cumplimiento de Objeto Social en formato PDF </label><br>
                                        <label ><a href="/images/{{$regconstancia->osc_d1}}" class="btn btn-danger" title="Solicitud de constancia de renovación de cumplimiento de objeto social en formato PDF"><i class="fa fa-file-pdf-o"></i>PDF
                                        </a>&nbsp;&nbsp;&nbsp;{{$regconstancia->osc_d1}}
                                        </label>
                                        <input type="hidden" name="doc_id1" id="doc_id1" value="16">
                                    </div>   
                                    <div class="col-xs-6 form-group">                                        
                                        <label>
                                        <iframe width="400" height="400" src="{{ asset('images/'.$regconstancia->osc_d1)}}" frameborder="0"></iframe> 
                                        </label>                                       
                                    </div>                                    
                                </div>
                                <div class="row">               
                                    <div class="col-xs-6 form-group">
                                        <label >Actualizar archivo digital de Solicitud de Constancia de Renovación de Cumplimiento de Objeto Social en formato PDF </label>
                                        <input type="file" class="text-md-center" style="color:red" name="osc_d1" id="osc_d1" placeholder="Subir archivo de Solicitud de constancia de renovación de cumplimiento de objeto social en formato PDF " >
                                    </div>      
                                </div>
                            @else     <!-- se captura archivo 1 -->
                                <div class="row">               
                                    <div class="col-xs-6 form-group">
                                        <label >Archivo digital de Solicitud de Constancia de Renovación de Cumplimiento de Objeto Social en formato PDF</label>
                                        <input type="file" class="text-md-center" style="color:red" name="osc_d1" id="osc_d1" placeholder="Subir archivo de Solicitud de constancia de renovación de cumplimiento de objeto social en formato PDF" >
                                        <input type="hidden" name="doc_id1" id="doc_id1" value="16">
                                    </div>                      
                                </div>                          
                            @endif 

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
                                    {!! Form::submit('Guardar',['class' => 'btn btn-success btn-flat pull-right']) !!}
                                    <a href="{{route('verConst')}}" role="button" id="cancelar" class="btn btn-danger">Cancelar</a>
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
        {!! JsValidator::formRequest('App\Http\Requests\constanciaRequest','#actualizarConst1') !!}
@endsection

@section('javascrpt')
@endsection
