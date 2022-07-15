@extends('sicinar.principal')

@section('title','Registro de Solicitud de constancia')

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
                <small> - Nueva </small>
            </h1>
            <ol class="breadcrumb">
                <li><img src="{{ asset('images/b5.jpg') }}" border="0" width="30" height="30">&nbsp;&nbsp;Solicitud de constancia - Nueva</li> 
            </ol>            
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12"> 
                    <div class="box box-success">
                        <p align="justify"><b style="color:red;">
                            Instrucciones:</b> <b style="color:green;">
                            Deberá subir en este apartado el archivo digital de Oficio de Solicitud de Cumplimiento del Objeto Social una vez cumplidos y actualizados los requisitos previos del punto 1 al 4.
                            Se requiere escanear a una resolución de 300 ppp en blanco y negro.
                            </b>
                        </p>                    

                        {!! Form::open(['route' => 'AltaNuevaConst', 'method' => 'POST','id' => 'nuevaConst', 'enctype' => 'multipart/form-data']) !!}

                        <div class="box-body">
                            <div class="row">                                
                                <div class="col-xs-8 form-group">
                                    <label >OSC</label>
                                    <select class="form-control m-bot15" name="osc_id" id="osc_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar OSC</option>
                                        @foreach($regosc as $osc)
                                            <option value="{{$osc->osc_id}}">{{$osc->osc_id.' '.$osc->osc_desc}}</option>
                                        @endforeach 
                                    </select>                                    
                                </div>   
                                <div class="col-xs-2 form-group">
                                    <label >Periodo fiscal </label>
                                    <select class="form-control m-bot15" name="periodo_id" id="periodo_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar periodo fiscal</option>
                                        @foreach($regperiodos as $periodo)
                                            <option value="{{$periodo->periodo_id}}">{{$periodo->periodo_desc}}</option>
                                        @endforeach
                                    </select>                                    
                                </div>                                                               
                            </div>     

                            <div class="row">
                                <div class="col-xs-12 form-group">
                                    <label style="background-color:yellow;color:red"><b>Nota importante:</b>&nbsp;&nbsp;El archivo digital, NO deberán ser mayores a 1,500 kBytes en tamaño.  </label>
                                </div>   
                            </div>  
                            <div class="row">               
                                <div class="col-xs-6 form-group">
                                    <label >Archivo digital de Solicitud de Constancia de Renovación de Cumplimiento de Objeto Social en formato PDF </label>
                                    <input type="hidden" name="doc_id1" id="doc_id1" value="16">
                                    <input type="file" class="text-md-center" style="color:red" name="osc_d1" id="osc_d1" placeholder="Subir archivo de Solicitud de constancia en formato PDF">
                                </div>   
                            </div>    
                         
                            <div class="row">
                                <div class="col-md-12 offset-md-5">
                                    {!! Form::submit('Registrar',['class' => 'btn btn-success btn-flat pull-right']) !!}
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
    {!! JsValidator::formRequest('App\Http\Requests\constanciaRequest','#nuevaConst') !!}
@endsection

@section('javascrpt')
@endsection
