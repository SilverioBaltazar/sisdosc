@extends('sicinar.principal')

@section('title','Ver Unidades de medida')

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
            <h1>Catálogos - Unidades de medida
                <small> Seleccionar para editar</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Menú</a></li>
                <li><a href="#">Catálogos </a></li>
                <li><a href="#">Unidades de medida  </a></li>         
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header" style="text-align:right;">
                            <a href="{{route('catumedidaexcel')}}" class="btn btn-success" title="Exportar catálogo de Unidades de medida a formato Excel"><i class="fa fa-file-excel-o"></i> Excel</a>                            
                            <a href="{{route('catumedidapdf')}}"   class="btn btn-danger"  title="Exportar catálogo de Unidades de medida a formato PDF  "><i class="fa fa-file-pdf-o"></i> PDF</a>
                            <a href="{{route('nuevaumedida')}}"    class="btn btn-primary btn_xs" title="Nueva Unidad de medida"><i class="fa fa-file-new-o"></i><span class="glyphicon glyphicon-plus"></span>Nueva Unidad de medida </a>                             
                        </div>
                        <div class="box-body">
                            <table id="tabla1" class="table table-striped table-bordered table-sm">
                                <thead style="color: brown;" class="justify">
                                    <tr>
                                        <th style="text-align:left;   vertical-align: middle;">Id.</th>
                                        <th style="text-align:left;   vertical-align: middle;">Unidad de medida </th>
                                        <th style="text-align:center; vertical-align: middle;">Activo / Inactivo</th>
                                        <th style="text-align:center; vertical-align: middle;">Fecha registro</th>
                                        <th style="text-align:center; vertical-align: middle;">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($regumedida as $medida)
                                    <tr>
                                        <td style="text-align:left; vertical-align: middle;">{{$medida->umed_id}}</td>
                                        <td style="text-align:left; vertical-align: middle;">{{$medida->umed_desc}}</td>
                                        @if($medida->umed_status == 'S')
                                            <td style="color:darkgreen;text-align:center; vertical-align: middle;" title="Activo"><i class="fa fa-check"></i>
                                            </td>                                            
                                        @else
                                            <td style="color:darkred; text-align:center; vertical-align: middle;" title="Inactivo"><i class="fa fa-times"></i>
                                            </td>                                            
                                        @endif
                                        <td style="text-align:center; vertical-align: middle;">{{date("d/m/Y", strtotime($medida->umed_fecreg))}}</td>
                                        <td style="text-align:center;">
                                            <a href="{{route('editarumedida',$medida->umed_id)}}" class="btn badge-warning" title="Editar Unidad de medida"><i class="fa fa-edit"></i></a>
                                            <a href="{{route('borrarumedida',$medida->umed_id)}}" class="btn badge-danger"  title="Borrar Unidad de medida" onclick="return confirm('¿Seguro que desea borrar la Unidad de medida?')"><i class="fa fa-times"></i></a>
                                        </td>                                                                                
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {!! $regumedida->appends(request()->input())->links() !!}
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