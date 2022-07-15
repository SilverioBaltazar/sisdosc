@extends('sicinar.principal')

@section('title','Editar acción o meta del programa anual')

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
                <small> Registro -  </small>                
                <small> Programa anual - editar acción o meta </small>           
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-success">

                        {!! Form::open(['route' => ['actualizardpa1',$regprogdanual->folio, $regprogdanual->partida], 'method' => 'PUT', 'id' => 'actualizardpa1', 'enctype' => 'multipart/form-data']) !!}
                        <div class="box-body">

                            <table id="tabla1" class="table table-hover table-striped">
                            @foreach($regprogeanual as $proganual)
                            <tr>                            
                                <td style="text-align:left; vertical-align: middle; color:green;"> 
                                    <input type="hidden" id="epprog_id" name="epprog_id" value="{{$proganual->epprog_id}}">  
                                    <label>Programa: </label>&nbsp;&nbsp;
                                    @foreach($regepprog as $prog)
                                        @if($prog->epprog_id == $proganual->epprog_id)
                                            {{Trim($prog->epprog_desc)}}
                                            @break
                                        @endif
                                    @endforeach
                                </td>
                                <td style="text-align:rigth; vertical-align: middle; color:green;">   
                                    <input type="hidden" id="folio" name="folio" value="{{$proganual->folio}}">  
                                    <label>Fecha de entrega: </label>&nbsp;&nbsp;{{$proganual->fecha_entrega2}}
                                </td>                                                                                                 
                            </tr>      
                            <tr>                            
                                <td style="text-align:left; vertical-align: middle; color:green;"> 
                                    <input type="hidden" id="epproy_id" name="epproy_id" value="{{$proganual->epproy_id}}">  
                                    <label>Proyecto: </label>&nbsp;&nbsp;
                                    @foreach($regepproy as $proy)
                                        @if($proy->epproy_id == $proganual->epproy_id)
                                            {{Trim($proy->epproy_desc)}}
                                            @break
                                        @endif
                                    @endforeach
                                </td>                                
                            </tr>      
                            <tr>                            
                                <td style="text-align:left; vertical-align: middle; color:green;"> 
                                    <input type="hidden" id="depen_id1" name="depen_id1" value="{{$proganual->depen_id1}}">  
                                    <label>Unidad Responsable: </label>&nbsp;&nbsp;
                                    @foreach($regdepen as $depen)
                                        @if($depen->depen_id == $proganual->depen_id1)
                                            {{Trim($depen->depen_desc)}}
                                            @break
                                        @endif
                                    @endforeach
                                </td>
                                <td style="text-align:rigth; vertical-align: middle; color:green;">   
                                    <input type="hidden" id="folio" name="folio" value="{{$proganual->folio}}">  
                                    <label>Folio: </label>&nbsp;&nbsp;{{$proganual->folio}}
                                </td>                                                                                                 
                            </tr>      
                            <tr>                            
                                <td style="text-align:left; vertical-align: middle; color:green;"> 
                                    <input type="hidden" id="depen_id2" name="depen_id2" value="{{$proganual->depen_id2}}">  
                                    <label>Unidad ejecutora: </label>&nbsp;&nbsp;
                                    @foreach($regdepen as $depen)
                                        @if($depen->depen_id == $proganual->depen_id2)
                                            {{Trim($depen->depen_desc)}}
                                            @break
                                        @endif
                                    @endforeach
                                </td>
                                <td style="text-align:rigth; vertical-align: middle; color:green;">   
                                    <input type="hidden" id="periodo_id"     name="periodo_id"     value="{{$proganual->periodo_id}}">  
                                    <input type="hidden" id="fecha_entrega"  name="fecha_entrega"  value="{{$proganual->fecha_entrega}}">  
                                    <input type="hidden" id="fecha_entrega2" name="fecha_entrega2" value="{{$proganual->fecha_entrega2}}">  
                                    <input type="hidden" id="fecha_entrega3" name="fecha_entrega3" value="{{$proganual->fecha_entrega3}}">                                  
                                    <label>Periodo fiscal: </label>{{$proganual->periodo_id}}
                                </td>   
                            </tr>   
                            @endforeach     
                            </table>  

                            @if (!empty($regprogdanual->soporte_01)||!is_null($regprogdanual->soporte_01))   
                                <div class="row">
                                    <div class="col-xs-6 form-group">
                                        <label >Archivo digital de ficha de soporte en formato PDF</label><br>
                                        <label ><a href="/storage/{{$regprogdanual->soporte_01}}" class="btn btn-danger" title="Archivo digital de ficha de soporte en formato PDF"><i class="fa fa-file-pdf-o"></i>PDF
                                        </a>   &nbsp;&nbsp;&nbsp;{{$regprogdanual->soporte_01}}
                                        </label>
                                    </div>   
                                    <div class="col-xs-6 form-group">                                        
                                        <label>
                                        <iframe width="400" height="300" src="{{ asset('storage/'.$regprogdanual->soporte_01)}}" frameborder="0"></iframe> 
                                        </label>                                       
                                    </div>                                        
                                </div>
                                <div class="row">
                                    <div class="col-xs-6 form-group">
                                        <label >Actualizar archivo digital de ficha de soporte en formato PDF</label>
                                        <input type="file" class="text-md-center" style="color:red" name="soporte_01" id="soporte_01" placeholder="Subir archivo de digital de ficha de soporte en formato PDF" >
                                    </div>      
                                </div>
                            @else     <!-- se captura archivo 1 -->
                                <div class="row">                            
                                    <div class="col-xs-6 form-group">
                                        <label >Archivo digital de ficha de soporte en formato PDF</label>
                                        <input type="file" class="text-md-center" style="color:red" name="soporte_01" id="soporte_01" placeholder="Subir archivo digital de ficha de soporte en formato PDF" >
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
                                    @foreach($regprogeanual as $progtrab)
                                       <a href="{{route('verdpa',$progtrab->folio)}}" role="button" id="cancelar" class="btn btn-danger">Cancelar
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
    {!! JsValidator::formRequest('App\Http\Requests\progdanual1Request','#actualizardpa1') !!}
@endsection

@section('javascrpt')
@endsection

