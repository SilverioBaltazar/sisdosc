@extends('sicinar.principal')

@section('title','Editar programa anual')

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
                <small> Programa anual   </small>                
                <small> Metas - editar </small>           
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-success">

                        {!! Form::open(['route' => ['actualizarpa',$regprogeanual->folio], 'method' => 'PUT', 'id' => 'actualizarpa', 'enctype' => 'multipart/form-data']) !!}
                        <div class="box-body">
                            <div class="row">   
                                <div class="col-xs-4 form-group">
                                    <input type="hidden" name="periodo_id" id="periodo_id" value="{{$regprogeanual->periodo_id}}"> 
                                    <input type="hidden" name="osc_id" id="osc_id" value="{{$regprogeanual->osc_id}}">    
                                    <label style="color:green; text-align:left; vertical-align: middle;">Periodo fiscal: </label>
                                    <td>&nbsp;&nbsp;{{$regprogeanual->periodo_id}} </td>                                    
                                </div>                                
                                <div class="col-xs-4 form-group align:rigth">
                                    <input type="hidden" name="folio" id="folio" value="{{$regprogeanual->folio}}"> 
                                    <label style="color:green; text-align:left; vertical-align: middle;">Folio: </label>
                                    <td>&nbsp;&nbsp;{{$regprogeanual->folio}}</td>    
                                </div>
                            </div>
                            <div class="row">    
                                <div class="col-xs-10 form-group">
                                    <label>Programa </label>
                                    <select class="form-control m-bot15" name="epprog_id" id="epprog_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar Programa </option>
                                        @foreach($regepprog as $prog)
                                            @if($prog->epprog_id == $regprogeanual->epprog_id)
                                                <option value="{{$prog->epprog_id}}" selected>{{$prog->epprog_id.' '.Trim($prog->epprog_desc)}}</option>
                                            @else                                        
                                               <option value="{{$prog->epprog_id}}">{{$prog->epprog_id.' '.Trim($prog->epprog_desc)}} </option>
                                            @endif
                                        @endforeach
                                    </select>                                 
                                </div>                             
                            </div>                            
                            <div class="row">    
                                <div class="col-xs-10 form-group">
                                    <label>Proyecto </label>
                                    <select class="form-control m-bot15" name="epproy_id" id="epproy_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar Proyecto </option>
                                        @foreach($regepproy as $proye)
                                            @if($proye->epproy_id == $regprogeanual->epproy_id)
                                                <option value="{{$proye->epproy_id}}" selected>{{$proye->epproy_id.' '.Trim($proye->epproy_desc)}}</option>
                                            @else                                        
                                                <option value="{{$proye->epproy_id}}">{{$proye->epproy_id.' '.Trim($proye->epproy_desc)}} </option>
                                            @endif
                                        @endforeach
                                    </select>      
                                </div>                             
                            </div>

                            <div class="row">    
                                <div class="col-xs-10 form-group">
                                    <label>Unidad responsable </label>
                                    <select class="form-control m-bot15" name="depen_id1" id="depen_id1" required>
                                        <option selected="true" disabled="disabled">Seleccionar Unidad responsable </option>
                                        @foreach($regdepen as $depen)
                                            @if($depen->depen_id == $regprogeanual->depen_id1)
                                                <option value="{{$depen->depen_id}}" selected>{{$depen->depen_id.' '.Trim($depen->depen_desc)}}</option>
                                            @else                                        
                                               <option value="{{$depen->depen_id}}">{{$depen->depen_id.' '.Trim($depen->depen_desc)}} </option>
                                            @endif
                                        @endforeach
                                    </select>                    
                                </div>                             
                            </div>
                            <div class="row">    
                                <div class="col-xs-10 form-group">
                                    <label>Unidad ejecutora </label>
                                    <select class="form-control m-bot15" name="depen_id2" id="depen_id2" required>
                                        <option selected="true" disabled="disabled">Seleccionar Unidad ejecutora </option>
                                        @foreach($regdepen as $depen)
                                            @if($depen->depen_id == $regprogeanual->depen_id2)
                                                <option value="{{$depen->depen_id}}" selected>{{$depen->depen_id.' '.Trim($depen->depen_desc)}}</option>
                                            @else                                        
                                               <option value="{{$depen->depen_id}}">{{$depen->depen_id.' '.Trim($depen->depen_desc)}} </option>
                                            @endif
                                        @endforeach
                                    </select>                                     
                                </div>                             
                            </div>                                                                                    
                            <div class="row">    
                                <div class="col-xs-4 form-group">
                                    <label >Tipo  de proyecto </label>
                                    <select class="form-control m-bot15" name="taccion_id" id="taccion_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar tipo de proyecto </option>
                                        @foreach($regtipometa as $meta)
                                            @if($meta->taccion_id == $regprogeanual->taccion_id)
                                                <option value="{{$meta->taccion_id}}" selected>{{$meta->taccion_desc}}</option>
                                            @else                                        
                                               <option value="{{$meta->taccion_id}}">{{$meta->taccion_desc}}</option>
                                            @endif
                                        @endforeach
                                    </select>                                    
                                </div> 
                            </div>

                            <div class="row">    
                                <div class="col-xs-4 form-group">
                                    <label >Fecha de entrega - Año </label>
                                    <select class="form-control m-bot15" name="periodo_id1" id="periodo_id1" required>
                                        <option selected="true" disabled="disabled">Seleccionar año de entrega </option>
                                        @foreach($reganios as $anio)
                                            @if($anio->anio_id == $regprogeanual->periodo_id1)
                                                <option value="{{$anio->anio_id}}" selected>{{$anio->anio_desc}}</option>
                                            @else                                        
                                                <option value="{{$anio->anio_id}}">{{$anio->anio_desc}}</option>
                                            @endif
                                        @endforeach
                                    </select>                                    
                                </div>   
                                <div class="col-xs-4 form-group">
                                    <label >Mes </label>
                                    <select class="form-control m-bot15" name="mes_id1" id="mes_id1" required>
                                        <option selected="true" disabled="disabled">Seleccionar mes de entrega </option>
                                        @foreach($regmeses as $mes)
                                            @if($mes->mes_id == $regprogeanual->mes_id1)
                                                <option value="{{$mes->mes_id}}" selected>{{$mes->mes_desc}}</option>
                                            @else                                        
                                               <option value="{{$mes->mes_id}}">{{$mes->mes_desc}} </option>
                                            @endif
                                        @endforeach
                                    </select>                                    
                                </div>    
                                <div class="col-xs-4 form-group">
                                    <label >Día </label>
                                    <select class="form-control m-bot15" name="dia_id1" id="dia_id1" required>
                                        <option selected="true" disabled="disabled">Seleccionar día de entrega </option>
                                        @foreach($regdias as $dia)
                                            @if($dia->dia_id == $regprogeanual->dia_id1)
                                                <option value="{{$dia->dia_id}}" selected>{{$dia->dia_desc}}</option>
                                            @else                                        
                                               <option value="{{$dia->dia_id}}">{{$dia->dia_desc}} </option>
                                            @endif
                                        @endforeach
                                    </select>                                    
                                </div>                                    
                            </div>

                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Responsable </label>
                                    <input type="text" class="form-control" name="responsable" id="responsable" placeholder="Responsable" value="{{trim($regprogeanual->responsable)}}" onkeypress="return soloLetras(event)" required>
                                </div>  
                                <div class="col-xs-4 form-group">
                                    <label >Elaboró </label>
                                    <input type="text" class="form-control" name="elaboro" id="elaboro" placeholder="Elaboró" value="{{trim($regprogeanual->elaboro)}}" onkeypress="return soloAlfa(event)" required>
                                </div>
                                <div class="col-xs-4 form-group">
                                    <label >Autorizó </label>
                                    <input type="text" class="form-control" name="autorizo" id="autorizo" placeholder="Autorizó" value="{{trim($regprogeanual->autorizo)}}" onkeypress="return soloLetras(event)" required>
                                </div>
                            </div>

                            <div class="row">                                             
                                <div class="col-xs-4 form-group">                        
                                    <label>Estado del programa anual activo o Inactivo </label>
                                    <select class="form-control m-bot15" name="status_1" id="status_1" required>
                                        @if($regprogeanual->status_1 == 'S')
                                            <option value="S" selected>Activo  </option>
                                            <option value="N">         Inactivo</option>
                                        @else
                                            <option value="S">         Activo  </option>
                                            <option value="N" selected>Inactivo</option>
                                        @endif
                                    </select>
                                </div>                                                                  
                            </div>

                            <div class="row">                                
                                <div class="col-xs-12 form-group">
                                    <label >Observaciones (1,500 caracteres)</label>
                                    <textarea class="form-control" name="obs_1" id="obs_1" rows="3" cols="120" placeholder="Observaciones (1,500 caracteres)" required>{{Trim($regprogeanual->obs_1)}}
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
    {!! JsValidator::formRequest('App\Http\Requests\progeanualRequest','#actualizarpa') !!}
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

