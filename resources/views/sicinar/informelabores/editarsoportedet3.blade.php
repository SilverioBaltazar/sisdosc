@extends('sicinar.principal')

@section('title','Editar soportes mensuales del PA')

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
                <small> Registro -     </small>                
                <small> Soportes - editar </small>           
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-success">

                        {!! Form::open(['route' => ['actualizarsoportedet3',$regprogdanual->folio, $regprogdanual->partida], 'method' => 'PUT', 'id' => 'actualizarsoportedet3', 'enctype' => 'multipart/form-data']) !!}
                        <div class="box-body">

                            <table id="tabla1" class="table table-hover table-striped">
                            @foreach($regprogeanual as $encpa)
                            <tr>
                                <td style="text-align:right; vertical-align: middle;"> </td>
                                <td style="text-align:right; vertical-align: middle;"> 
                                   
                                   
                                    <a href="{{route('verInformes')}}" role="button" id="cancelar" class="btn btn-success"><small>Regresar a informe de avances </small>
                                    </a>
                                   
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
                                @foreach($regepproy as $proy)
                                    @if($proy->epproy_id == $encpa->epproy_id)
                                        {{Trim($proy->epproy_desc)}}
                                        @break
                                    @endif
                                @endforeach
                                </b><br>                    
                                Unidad responsable: <b style="text-align:left; vertical-align: middle; color:green;font-size:10px;">
                                @foreach($regdepen as $depen)
                                    @if($depen->depen_id == $encpa->depen_id1)
                                        {{Trim($depen->depen_desc)}}
                                        @break
                                    @endif
                                @endforeach
                                </b><br>                    
                                Unidad ejecutora: <b style="text-align:left; vertical-align: middle; color:green;font-size:10px;">
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                @foreach($regdepen as $depen)
                                    @if($depen->depen_id == $encpa->depen_id2)
                                        {{Trim($depen->depen_desc)}}
                                        @break
                                    @endif
                                @endforeach
                                </b>              
                                </td>

                                <td style="border:0; text-align:left;font-size:10px;">
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


                            @if (!empty($regprogdanual->soporte_03)||!is_null($regprogdanual->soporte_03))   
                                <div class="row">
                                    <div class="col-xs-6 form-group">
                                        <label >Archivo digital de soporte de marzo en formato PDF</label><br>
                                        <label ><a href="/storage/{{$regprogdanual->soporte_03}}" class="btn btn-danger" title="Archivo digital de ficha de soporte en formato PDF"><i class="fa fa-file-pdf-o"></i>PDF
                                        </a>   &nbsp;&nbsp;&nbsp;{{$regprogdanual->soporte_03}}
                                        </label>
                                    </div>   
                                    <div class="col-xs-6 form-group">                                        
                                        <label>
                                        <iframe width="400" height="300" src="{{ asset('storage/'.$regprogdanual->soporte_03)}}" frameborder="0"></iframe> 
                                        </label>                                       
                                    </div>                                        
                                </div>
                                <div class="row">
                                    <div class="col-xs-6 form-group">
                                        <label >Actualizar archivo digital de soporte de marzo en formato PDF</label>
                                        <input type="file" class="text-md-center" style="color:red" name="soporte_03" id="soporte_03" placeholder="Subir archivo de digital de ficha de soporte en formato PDF" >
                                    </div>      
                                </div>
                            @else     <!-- se captura archivo 1 -->
                                <div class="row">                            
                                    <div class="col-xs-6 form-group">
                                        <label >Archivo digital de soporte de marzo en formato PDF</label>
                                        <input type="file" class="text-md-center" style="color:red" name="soporte_03" id="soporte_03" placeholder="Subir archivo digital de ficha de soporte en formato PDF" >
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
                                    {!! Form::submit('Registrar',['class' => 'btn btn-success btn-flat pull-right']) !!}
                                    @foreach($regprogeanual as $encpa)
                                       <a href="{{route('versoportesdet',$encpa->folio)}}" role="button" id="cancelar" class="btn btn-danger">Cancelar
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
    {!! JsValidator::formRequest('App\Http\Requests\soportedet3Request','#actualizarsoportedet3') !!}
@endsection

@section('javascrpt')
@endsection

