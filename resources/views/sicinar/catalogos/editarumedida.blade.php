@extends('sicinar.principal')

@section('title','Editar Unidad de medida')

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
            <h1>
                Menú
                <small> Catálogos - Unidades de medida - Editar</small>
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        
                        {!! Form::open(['route' => ['actualizarumedida',$regumedida->umed_id], 'method' => 'PUT', 'id' => 'actualizarumedida']) !!}
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-12 offset-md-12">
                                    <input type="hidden" name="umed_id" id="umed_id" value="{{$regumedida->umed_id}}"> 
                                    <label>Id.: {{$regumedida->umed_id}}</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label>* Unidad de medida </label>
                                    <input type="text" class="form-control" name="umed_desc" id="umed_desc" placeholder="Unidad de medida" value="{{$regumedida->umed_desc}}" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-3 form-group">
                                    <label>* Activo o Inactivo </label>
                                    <select class="form-control m-bot15" name="umed_status" required>
                                        @if($regumedida->umed_status == 'S')
                                            <option value="S" selected>SI</option>
                                            <option value="N">NO</option>
                                        @else
                                            <option value="S">SI</option>
                                            <option value="N" selected>NO</option>
                                        @endif
                                    </select>
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
                                    <a href="{{route('verumedida')}}" role="button" id="cancelar" class="btn btn-danger">Cancelar</a>
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
    {!! JsValidator::formRequest('App\Http\Requests\umedidaRequest','#actualizarumedida') !!}
@endsection

@section('javascrpt')
@endsection
