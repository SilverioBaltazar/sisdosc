@extends('sicinar.principal')

@section('title','Registrar visita de diligiencia')

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
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Menú
                <small> Agenda de diligencias - Visitas - editar</small>
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-success">

                        {!! Form::open(['route' => ['actualizarVisita',$regvisita->visita_folio], 'method' => 'PUT', 'id' => 'actualizarVisita', 'enctype' => 'multipart/form-data']) !!}
                        <div class="box-body">

                            <div class="row">    
                                <div class="col-xs-6 form-group" style="color:green;font-size:12px; text-align:left; vertical-align: middle;">
                                    <label >OSC</label><br>
                                    <b>
                                    @foreach($regosc as $osc)
                                        @if($osc->osc_id == $regvisita->osc_id)
                                            {{$osc->osc_desc}}
                                            @break 
                                        @endif
                                    @endforeach
                                    </b>
                                </div> 
                                <div class="col-xs-2 form-group" style="color:green;font-size:12px; text-align:right; vertical-align: middle;">
                                    <label >Oficio No.</label><br>
                                    <b>{{Trim($regvisita->visita_oficio)}}</b>
                                </div>                                                        
                                <div class="col-xs-2 form-group" style="color:green;font-size:12px; text-align:right; vertical-align: middle;">
                                    <label >Folio Sistema</label><br>
                                    <b>{{Trim($regvisita->visita_folio)}}</b>
                                </div>                                    
                            </div>

                            <div class="row">    
                                <div class="col-xs-8 form-group">
                                    <label >Domicilio </label>
                                    <input type="text" class="form-control" name="visita_dom" id="visita_dom" placeholder="Domicilio a donde va a ser la diligencia" value="{{Trim($regvisita->visita_dom)}}" required>
                                </div>    
                            </div>                            

                            <div class="row">                                
                                <div class="col-xs-4 form-group">
                                    <label >Entidad </label>
                                    <select class="form-control m-bot15" name="entidad2_id" id="entidad2_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar entidad</option>
                                        @foreach($regentidades as $entidad)
                                            @if($entidad->entidadfederativa_id == $regvisita->entidad2_id)
                                                <option value="{{$entidad->entidadfederativa_id}}" selected>{{$entidad->entidadfederativa_desc}}</option>
                                            @else 
                                                <option value="{{$entidad->entidadfederativa_id}}">{{$entidad->entidadfederativa_desc}}</option>
                                            @endif
                                        @endforeach
                                    </select>                                  
                                </div>                                                                              
                                <div class="col-xs-4 form-group">
                                    <label >Municipio</label>
                                    <select class="form-control m-bot15" name="municipio2_id" id="municipio2_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar municipio</option>
                                        @foreach($regmunicipio as $municipio)
                                            @if($municipio->entidadfederativaid == $regvisita->entidad2_id && 
                                                $municipio->municipioid == $regvisita->municipio2_id)                                            
                                                <option value="{{$municipio->municipioid}}" selected>{{$municipio->entidadfederativa_desc.'-'.$municipio->municipionombre}}</option>
                                            @else 
                                               <option value="{{$municipio->municipioid}}">{{$municipio->entidadfederativa_desc.'-'.$municipio->municipionombre}}
                                               </option>
                                            @endif
                                        @endforeach
                                    </select>                                  
                                </div>  
                            </div>

                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Año que inicio la diligencia</label>
                                    <select class="form-control m-bot15" name="periodo_id" id="periodo_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar año </option>
                                        @foreach($regperiodos as $periodo)
                                            @if($periodo->periodo_id == $regvisita->periodo_id)
                                                <option value="{{$periodo->periodo_id}}" selected>{{$periodo->periodo_id}}</option>
                                            @else                                        
                                               <option value="{{$periodo->periodo_id}}">{{$periodo->periodo_id}} </option>
                                            @endif
                                        @endforeach
                                    </select>                                    
                                </div>                               
                                <div class="col-xs-4 form-group">
                                    <label >Mes que inicio </label>
                                    <select class="form-control m-bot15" name="mes_id" id="mes_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar mes </option>
                                        @foreach($regmeses as $mes)
                                            @if($mes->mes_id == $regvisita->mes_id)
                                                <option value="{{$mes->mes_id}}" selected>{{$mes->mes_desc}}</option>
                                            @else                                        
                                               <option value="{{$mes->mes_id}}">{{$mes->mes_desc}} </option>
                                            @endif
                                        @endforeach
                                    </select>                                    
                                </div>                                   
                                <div class="col-xs-2 form-group">
                                    <label >Dia que inicio</label>
                                    <select class="form-control m-bot15" name="dia_id" id="dia_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar dia </option>
                                        @foreach($regdias as $dia)
                                            @if($dia->dia_id == $regvisita->dia_id)
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
                                    <label >Hora de inicio </label>
                                    <select class="form-control m-bot15" name="hora_id" id="hora_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar hora </option>
                                        @foreach($reghoras as $hora)
                                            @if($hora->hora_id == $regvisita->hora_id)
                                                <option value="{{$hora->hora_id}}" selected>{{$hora->hora_desc}}</option>
                                            @else                                        
                                               <option value="{{$hora->hora_id}}">{{$hora->hora_desc}} </option>
                                            @endif
                                        @endforeach
                                    </select>                                    
                                </div>  
                                <div class="col-xs-4 form-group">
                                    <label >Minutos de inicio </label>
                                    <select class="form-control m-bot15" name="num_id" id="num_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar minutos </option>
                                        @foreach($regminutos as $min)
                                            @if($min->num_id == $regvisita->num2_id)
                                                <option value="{{$min->num_id}}" selected>{{$min->num_desc}}</option>
                                            @else                                        
                                               <option value="{{$min->num_id}}">{{$min->num_desc}} </option>
                                            @endif
                                        @endforeach
                                    </select>                                    
                                </div>                                   
                            </div>         
                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Año que terminó la diligencia</label>
                                    <select class="form-control m-bot15" name="periodo2_id" id="periodo2_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar año </option>
                                        @foreach($regperiodos as $periodo)
                                            @if($periodo->periodo_id == $regvisita->periodo2_id)
                                                <option value="{{$periodo->periodo_id}}" selected>{{$periodo->periodo_id}}</option>
                                            @else                                        
                                               <option value="{{$periodo->periodo_id}}">{{$periodo->periodo_id}} </option>
                                            @endif
                                        @endforeach
                                    </select>                                    
                                </div>                               
                                <div class="col-xs-4 form-group">
                                    <label >Mes que terminó </label>
                                    <select class="form-control m-bot15" name="mes2_id" id="mes2_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar mes </option>
                                        @foreach($regmeses as $mes)
                                            @if($mes->mes_id == $regvisita->mes2_id)
                                                <option value="{{$mes->mes_id}}" selected>{{$mes->mes_desc}}</option>
                                            @else                                        
                                               <option value="{{$mes->mes_id}}">{{$mes->mes_desc}} </option>
                                            @endif
                                        @endforeach
                                    </select>                                    
                                </div>                                   
                                <div class="col-xs-2 form-group">
                                    <label >Dia que terminó</label>
                                    <select class="form-control m-bot15" name="dia2_id" id="dia2_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar dia </option>
                                        @foreach($regdias as $dia)
                                            @if($dia->dia_id == $regvisita->dia2_id)
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
                                    <label >Hora que terminó </label>
                                    <select class="form-control m-bot15" name="hora2_id" id="hora2_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar hora </option>
                                        @foreach($reghoras as $hora)
                                            @if($hora->hora_id == $regvisita->hora2_id)
                                                <option value="{{$hora->hora_id}}" selected>{{$hora->hora_desc}}</option>
                                            @else                                        
                                               <option value="{{$hora->hora_id}}">{{$hora->hora_desc}} </option>
                                            @endif
                                        @endforeach
                                    </select>                                    
                                </div>  
                                <div class="col-xs-4 form-group">
                                    <label >Minutos que termino </label>
                                    <select class="form-control m-bot15" name="num2_id" id="num2_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar minutos </option>
                                        @foreach($regminutos as $min)
                                            @if($min->num_id == $regvisita->num2_id)
                                                <option value="{{$min->num_id}}" selected>{{$min->num_desc}}</option>
                                            @else                                        
                                               <option value="{{$min->num_id}}">{{$min->num_desc}} </option>
                                            @endif
                                        @endforeach
                                    </select>                                    
                                </div>                                   
                            </div>         

                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Auditado </label>
                                    <input type="text" class="form-control" name="visita_auditado1" id="visita_auditado1" placeholder="Auditado" value="{{Trim($regvisita->visita_auditado1)}}" required>
                                </div>  
                                <div class="col-xs-4 form-group">
                                    <label >Puesto</label>
                                    <input type="text" class="form-control" name="visita_puesto1" id="visita_puesto1" placeholder="Puesto del auditado" value="{{Trim($regvisita->visita_puesto1)}}" required>
                                </div>                                
                            </div>
                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Se identifica con </label>
                                    <input type="text" class="form-control" name="visita_ident1" id="visita_ident1" placeholder="Se identifica con" value="{{Trim($regvisita->visita_ident1)}}" required>
                                </div>  
                            </div>                            

                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Representante de la OSC</label>
                                    <input type="text" class="form-control" name="visita_auditado2" id="visita_auditado2" placeholder="Representante de la OSC" value="{{$regvisita->visita_auditado2}}" required>
                                </div>   
                                <div class="col-xs-4 form-group"> 
                                    <label >Puesto del representante de la OSC</label>
                                    <input type="text" class="form-control" name="visita_puesto2" id="visita_puesto2" placeholder="Puesto del representante de la OSC" value="{{Trim($regvisita->regvisita_puesto2)}}" required>
                                </div>                                          
                            </div>

                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Pesona que atendio la diligencia en la OSC</label>
                                    <input type="text" class="form-control" name="visita_auditado3" id="visita_auditado3" placeholder="Persona que atendio la diligencia" value="{{$regvisita->visita_auditado3}}" required>
                                </div>                                                                        
                            </div>

                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Responsable de la DPSE que realizo la diligencia</label>
                                    <input type="text" class="form-control" name="visita_auditor2" id="visita_auditor2" placeholder="Responsable de la DGPS que realizo la diligencia" value="{{$regvisita->visita_auditor2}}" required>
                                </div>   
                                <div class="col-xs-4 form-group">
                                    <label >Personal del DPSE presente en la diligencia</label>
                                    <input type="text" class="form-control" name="visita_auditor1" id="visita_auditor1" placeholder="Personal de la DGPS presente en la diligencia" value="{{Trim($regvisita->visita_auditor1)}}" required>
                                </div>                                                                       
                            </div>

                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Testigo 1</label>
                                    <input type="text" class="form-control" name="visita_testigo1" id="visita_testigo1" placeholder="Testigo 1" value="{{$regvisita->visita_testigo1}}" required>
                                </div>   
                                <div class="col-xs-4 form-group">
                                    <label >Se identifica con </label>
                                    <input type="text" class="form-control" name="visita_ident2" id="visita_ident2" placeholder="Se identifica con" value="{{Trim($regvisita->visita_ident2)}}" required>
                                </div>  
                            </div>                            
                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Testigo 2</label>
                                    <input type="text" class="form-control" name="visita_testigo2" id="visita_testigo2" placeholder="Testigo 2" value="{{Trim($regvisita->visita_testigo2)}}" required>
                                </div>       
                                <div class="col-xs-4 form-group">
                                    <label >Se identifica con </label>
                                    <input type="text" class="form-control" name="visita_ident3" id="visita_ident3" placeholder="Se identifica con" value="{{Trim($regvisita->visita_ident3)}}" required>
                                </div>                                                                                                  
                            </div>                            

                            <div class="row">                                                             
                                <div class="col-xs-4 form-group">                        
                                    <label>Estado de la diligencia </label>
                                    <select class="form-control m-bot15" name="visita_edo" id="visita_edo" required>
                                        @if($regvisita->visita_edo == '1')
                                            <option value="0"         >En proceso</option>
                                            <option value="1" selected>Cerrada   </option>
                                            <option value="2"         >Cancelada </option>
                                        @else
                                            @if($regvisita->visita_edo == '2')
                                               <option value="0"         >En proceso</option>
                                               <option value="1"         >Cerrada   </option>
                                               <option value="2" selected>Cancelada </option>
                                            @else
                                               <option value="0" selected>En proceso</option>
                                               <option value="1"         >Cerrada   </option>
                                               <option value="2"         >Cancelada </option>
                                            @endif
                                        @endif
                                    </select>
                                </div>  
                                <div class="col-xs-4 form-group">                        
                                    <label>¿Se realizo visita de diligencia? </label>
                                    <select class="form-control m-bot15" name="visita_status2" id="visita_status2" required>
                                        @if($regvisita->status2 == 'S')
                                            <option value="S" selected>Si </option>
                                            <option value="N"         >No </option>
                                        @else
                                            <option value="S"         >Si </option>
                                            <option value="N" selected>No </option>
                                        @endif
                                    </select>
                                </div>              
                            </div>

                            <div class="row">                                
                                <div class="col-xs-12 form-group">
                                    <label >Instrumentos (4,000 caracteres)</label>
                                    <textarea class="form-control" name="visita_criterios" id="visita_criterios" rows="6" cols="120" placeholder="Criterios de verificación de la visita de diligencia" required>{{Trim($regvisita->visita_criterios)}}
                                    </textarea>
                                </div>                                
                            </div>
                            <div class="row">                                
                                <div class="col-xs-12 form-group">
                                    <label >Visto de observaciones (4,000 caracteres)</label>
                                    <textarea class="form-control" name="visita_visto" id="visita_visto" rows="6" cols="120" placeholder="Visto de la diligencia" required>{{Trim($regvisita->visita_visto)}}
                                    </textarea>
                                </div>                                
                            </div>
                            <div class="row">                                
                                <div class="col-xs-12 form-group">
                                    <label >Pruebas en relación a los hechos u omisiones (4,000 caracteres)</label>
                                    <textarea class="form-control" name="visita_recomen" id="visita_recomen" rows="6" cols="120" placeholder="Recomendaciones" required>{{Trim($regvisita->visita_recomen)}}
                                    </textarea>
                                </div>                                
                            </div>                                                         

                            <div class="row">
                                <div class="col-xs-12 form-group">
                                    <label style="background-color:yellow;color:red"><b>Nota importante:</b>&nbsp;&nbsp;El archivo digital PDF, NO deberá ser mayor a 1,500 kBytes en tamaño.  </label>
                                </div>   
                            </div> 
                            @if(!empty($regvisita->p_obs01)||(!is_null($regvisita->p_obs01)))
                                <div class="row">               
                                    <div class="col-xs-6 form-group"> 
                                        <label >Archivo digital de Acta de visita Verificación en formato PDF </label><br>
                                        <a href="/images/{{$regvisita->p_obs01}}" class="btn btn-danger" title="Archivo digital de Acta de visita Verificación"><i class="fa fa-file-pdf-o"></i><small>PDF</small>
                                        </a>{{$regvisita->p_obs01}}
                                    </div>
                                    <div class="col-xs-6 form-group">                                        
                                        <label>
                                        <iframe width="400" height="400" src="{{asset('images/'.$regvisita->p_obs01)}}" frameborder="0"></iframe> 
                                        </label>   
                                    </div>                                        
                                </div> 
                                <div class="row">
                                    <div class="col-xs-4 form-group">
                                        <label >Actualizar archivo digital de Acta de visita de Verificación</label>
                                        <input type="file" class="text-md-center" style="color:red" name="p_obs01" id="p_obs01" placeholder="Subir archivo de Acta de Visita de Verificación" >
                                    </div>      
                                </div>
                            @else     <!-- se captura archivo 1 -->
                                <div class="row">
                                    <div class="col-xs-4 form-group">
                                        <label >Archivo de digital de Acta de visita de Verificación en formato PDF</label>
                                        <input type="file" class="text-md-center" style="color:red" name="p_obs01" id="p_obs01" placeholder="Subir archivo de FActa de Visita de Verificación en formato PDF" >
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
                                    <a href="{{route('verVisitas')}}" role="button" id="cancelar" class="btn btn-danger">Cancelar</a>
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
    {!! JsValidator::formRequest('App\Http\Requests\visitaRequest','#actualizarVisita') !!}
@endsection

@section('javascrpt')
@endsection
