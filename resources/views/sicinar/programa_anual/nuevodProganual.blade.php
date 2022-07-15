@extends('sicinar.principal')

@section('title','Nueva acción o meta')

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
                <small> Programa anual  -</small>                
                <small> Metas - Nueva acción </small>                
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-success">
                        
                        {!! Form::open(['route' => 'altanuevodpa', 'method' => 'POST','id' => 'nuevodpa', 'enctype' => 'multipart/form-data']) !!}
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
                                Fecha de entrega:<b style="text-align:left; vertical-align: middle; color:green;font-size:10px;">
                                {{$encpa->fecha_entrega2}}
                                </b><br>
                                Periodo fiscal:<b style="text-align:left; vertical-align: middle; color:green;font-size:10px;">
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                {{$encpa->periodo_id}}</b><br>
                                Folio:<b style="text-align:left; vertical-align: middle; color:green;font-size:10px;">
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                {{$encpa->folio}}</b><br>
                                <b></b>
                                </td>
                            </tr>
                            @endforeach             
                            </table>
    
                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <div class="col-xs-12">
                                        <label >No. IGOB </label>
                                        <input type="text" class="form-control" name="lgob_cod" id="lgob_cod" placeholder="Digitar código LGOB" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <div class="col-xs-12">
                                        <label >Id. SPP </label>
                                        <input type="text" class="form-control" name="ciprep_id" id="ciprep_id" placeholder="Digitar Id. CIPREP" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-12 form-group">
                                    <label >Acción o meta (4,000 caracteres)</label>
                                    <textarea class="form-control" name="actividad_desc" id="actividad_desc" rows="2" cols="120" placeholder="Acción o meta (4,000 caracteres)" required>
                                    </textarea>
                                </div>                                
                            </div>    

                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Tipo de acción o meta </label>
                                    <select class="form-control m-bot15" name="taccion_id" id="taccion_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar tipo de acción o meta </option>
                                        @foreach($regtipometa as $tipo)
                                            <option value="{{$tipo->taccion_id}}">{{$tipo->taccion_desc}}</option>
                                        @endforeach
                                    </select>                                  
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Unidad de medida </label>
                                    <select class="form-control m-bot15" name="umed_id" id="umed_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar unidad de medida </option>
                                        @foreach($regumedida as $umedida)
                                            <option value="{{$umedida->umed_id}}">{{$umedida->umed_desc}}</option>
                                        @endforeach
                                    </select>                                  
                                </div>
                            </div>
 
                            <div class="row" style="background-color:#800000;color:white;text-align:center;">
                                <div class="col-xs-12 form-group">
                                    <label><b>  Metas </b> </label>
                                </div>   
                            </div> 

                            <div class="row">                                                                
                                <div class="col-xs-2 form-group">
                                    <label>Anual </label>
                                    <input required autocomplete="off" id="totp_01" name="totp_01" min="0" max="999999999.99" class="form-control" type="decimal(9,2)" placeholder="Meta anual" value="0" required>
                                </div>
                            </div>                            

                            <div class="row">
                                <div class="col-xs-1 form-group">
                                    <label >ene </label>
                                    <input type="number" min="0" max="999999999999" class="form-control" name="mesp_01" id="mesp_01" placeholder="Meta programa de enero" required>
                                </div> 
                                <div class="col-xs-1 form-group">
                                    <label >feb </label>
                                    <input type="number" min="0" max="999999999999" class="form-control" name="mesp_02" id="mesp_02" placeholder="Meta programa de febrero" required>
                                </div> 
                                <div class="col-xs-1 form-group">
                                    <label >mar </label>
                                    <input type="number" min="0" max="999999999999" class="form-control" name="mesp_03" id="mesp_03" placeholder="Meta programa de marzo" required>
                                </div> 
                                <div class="col-xs-1 form-group">
                                    <label >abr </label>
                                    <input type="number" min="0" max="999999999999" class="form-control" name="mesp_04" id="mesp_04" placeholder="Meta programa de abril" required>
                                </div> 
                                <div class="col-xs-1 form-group">
                                    <label >may </label>
                                    <input type="number" min="0" max="999999999999" class="form-control" name="mesp_05" id="mesp_05" placeholder="Meta programa de mayo" required>
                                </div> 
                                <div class="col-xs-1 form-group">
                                    <label >jun </label>
                                    <input type="number" min="0" max="999999999999" class="form-control" name="mesp_06" id="mesp_06" placeholder="Meta programa de junio" required>
                                </div> 
                            </div>

                            <div class="row">
                                <div class="col-xs-1 form-group">
                                    <label >jul </label>
                                    <input type="number" min="0" max="999999999999" class="form-control" name="mesp_07" id="mesp_07" placeholder="Meta programa de julio" required>
                                </div> 
                                <div class="col-xs-1 form-group">
                                    <label >ago </label>
                                    <input type="number" min="0" max="999999999999" class="form-control" name="mesp_08" id="mesp_08" placeholder="Meta programa de agosto" required>
                                </div> 
                                <div class="col-xs-1 form-group">
                                    <label >sep </label>
                                    <input type="number" min="0" max="999999999999" class="form-control" name="mesp_09" id="mesp_09" placeholder="Meta programa de septiembre" required>
                                </div> 
                                <div class="col-xs-1 form-group">
                                    <label >oct </label>
                                    <input type="number" min="0" max="999999999999" class="form-control" name="mesp_10" id="mesp_10" placeholder="Meta programa de octubre" required>
                                </div> 
                                <div class="col-xs-1 form-group">
                                    <label >nov </label>
                                    <input type="number" min="0" max="999999999999" class="form-control" name="mesp_11" id="mesp_11" placeholder="Meta programa de noviembre" required>
                                </div> 
                                <div class="col-xs-1 form-group">
                                    <label >dic </label>
                                    <input type="number" min="0" max="999999999999" class="form-control" name="mesp_12" id="mesp_12" placeholder="Meta programa de diciembre" required>
                                </div> 
                            </div>

                            <div class="row">
                                <div class="col-xs-12 form-group">
                                    <label >Objetivo (4,000 caracteres)</label>
                                    <textarea class="form-control" name="objetivo_desc" id="objetivo_desc" rows="3" cols="120" placeholder="Objetivo (4,000 caracteres)" required>
                                    </textarea>
                                </div>                                
                            </div>                             
                            <div class="row">
                                <div class="col-xs-12 form-group">
                                    <label >Descripción operacional (4,000 caracteres)</label>
                                    <textarea class="form-control" name="operacional_desc" id="operacional_desc" rows="3" cols="120" placeholder="Descripción operacional (4,000 caracteres)" required>
                                    </textarea>
                                </div>                                
                            </div>                                   

                            <div class="row">
                                <div class="col-md-12 offset-md-5">
                                    {!! Form::submit('Registrar',['class' => 'btn btn-success btn-flat pull-right']) !!}
                                    @foreach($regprogeanual as $epa)
                                       <a href="{{route('verdpa',$epa->folio)}}" role="button" id="cancelar" class="btn btn-danger">Cancelar
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
    {!! JsValidator::formRequest('App\Http\Requests\progdanualRequest','#nuevodpa') !!}
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