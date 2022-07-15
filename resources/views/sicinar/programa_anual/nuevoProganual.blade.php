@extends('sicinar.principal')

@section('title','Nuevo Programa de trabajo')

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
    <meta charset="utf-8">
    <div class="content-wrapper">
        <section class="content-header">
            <h1>Menú
                <small> Programa anual</small>                
                <small> Metas - Nuevo</small>                
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-success">
                        
                        {!! Form::open(['route' => 'altanuevopa', 'method' => 'POST','id' => 'nuevopa', 'enctype' => 'multipart/form-data']) !!}
                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-4 form-group">
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
                                <div class="col-xs-10 form-group">
                                    <label >Programa  </label>
                                    <select class="form-control m-bot15" name="epprog_id" id="epprog_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar programa </option>
                                        @foreach($regepprog as $prog)
                                            <option value="{{$prog->epprog_id}}">{{$prog->epprog_id.' '.Trim($prog->epprog_desc)}}</option>
                                        @endforeach
                                    </select>                                    
                                </div>                       
                            </div>

                            <div class="row">                                
                                <div class="col-xs-10 form-group">
                                    <label >Proyecto  </label>
                                    <select class="form-control m-bot15" name="epproy_id" id="epproy_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar proyecto </option>
                                        @foreach($regepproy as $proye)
                                            <option value="{{$proye->epproy_id}}">{{$proye->epproy_id.' '.Trim($proye->epproy_desc)}}</option>
                                        @endforeach
                                    </select>                                    
                                </div>                                                                  
                            </div>

                            <div class="row">
                                <div class="col-xs-10 form-group">
                                    <label >Unidad responsable  </label>
                                    <select class="form-control m-bot15" name="depen_id1" id="depene_id1" required>
                                        <option selected="true" disabled="disabled">Seleccionar unidad responsable </option>
                                        @foreach($regdepen as $depen)
                                            <option value="{{$depen->depen_id}}">{{$depen->depen_id.' '.Trim($depen->depen_desc)}}</option>
                                        @endforeach
                                    </select>                                    
                                </div>              
                            </div>

                            <div class="row">                                         
                                <div class="col-xs-10 form-group">
                                    <label >Unidad ejecutora  </label>
                                    <select class="form-control m-bot15" name="depen_id2" id="depene_id2" required>
                                        <option selected="true" disabled="disabled">Seleccionar unidad ejecutora </option>
                                        @foreach($regdepen as $depen)
                                            <option value="{{$depen->depen_id}}">{{$depen->depen_id.' '.Trim($depen->depen_desc)}}</option>
                                        @endforeach
                                    </select>                                    
                                </div>                                                                  
                            </div>
                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Tipo de proyecto </label>
                                    <select class="form-control m-bot15" name="taccion_id" id="taccion_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar tipo de proyecto</option>
                                        @foreach($regtipometa as $tipo)
                                            <option value="{{$tipo->taccion_id}}">{{$tipo->taccion_desc}}</option>
                                        @endforeach
                                    </select>                                  
                                </div>
                            </div>

                            <div class="row">    
                                <div class="col-xs-3 form-group">
                                    <label >Fecha de entrega - Año </label>
                                    <select class="form-control m-bot15" name="periodo_id1" id="periodo_id1" required>
                                        <option selected="true" disabled="disabled">Seleccionar año de entrega</option>
                                        @foreach($reganios as $anio)
                                            <option value="{{$anio->anio_id}}">{{$anio->anio_desc}}</option>
                                        @endforeach
                                    </select>                                    
                                </div>   
                                <div class="col-xs-2 form-group">
                                    <label >Mes </label>
                                    <select class="form-control m-bot15" name="mes_id1" id="mes_id1" required>
                                        <option selected="true" disabled="disabled">Seleccionar mes de entrega</option>
                                        @foreach($regmeses as $mes)
                                            <option value="{{$mes->mes_id}}">{{$mes->mes_desc}} </option>
                                        @endforeach
                                    </select>                                    
                                </div>    
                                <div class="col-xs-2 form-group">
                                    <label >Día </label>
                                    <select class="form-control m-bot15" name="dia_id1" id="dia_id1" required>
                                        <option selected="true" disabled="disabled">Seleccionar día de entrega</option>
                                        @foreach($regdias as $dia)
                                            <option value="{{$dia->dia_id}}">{{$dia->dia_desc}} </option>
                                        @endforeach
                                    </select>                                    
                                </div>                                    
                            </div>

                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Responsable </label>
                                    <input type="text" class="form-control" name="responsable" id="responsable" placeholder="Responsable" onkeypress="return soloLetras(event)" required>
                                </div>  
                                <div class="col-xs-4 form-group">
                                    <label >Elaboró </label>
                                    <input type="text" class="form-control" name="elaboro" id="elaboro" placeholder="Elaboró" onkeypress="return soloAlfa(event)" required>
                                </div>
                                <div class="col-xs-4 form-group">
                                    <label >Autorizó </label>
                                    <input type="text" class="form-control" name="autorizo" id="autorizo" placeholder="Autorizó" onkeypress="return soloLetras(event)" required>
                                </div>
                            </div>
                                                      
                            <div class="row">
                                <div class="col-xs-12 form-group">
                                    <label >Observaciones (1,500 caracteres)</label>
                                    <textarea class="form-control" name="obs_1" id="obs_1" rows="2" cols="120" placeholder="Observaciones 1,500 caracteres" required>
                                    </textarea>
                                </div>                                
                            </div>                                                        

                            <div class="row">
                                <div class="col-md-12 offset-md-5">
                                    {!! Form::submit('Registrar',['class' => 'btn btn-success btn-flat pull-right']) !!}
                                    <a href="{{route('verpa')}}" role="button" id="cancelar" class="btn btn-danger">Cancelar</a>
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
    {!! JsValidator::formRequest('App\Http\Requests\progeanualRequest','#nuevopa') !!}
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