@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Ver Proyecto | @parent
@stop

@section('header_styles')
    <style>
    .color_text{
    color:#B3B3B3;
     }
    </style>
@stop

{{-- Page content --}}
@section('content')
  <!-- Content Header (Page header) -->
  <section class="content-header" style="background-color:#12160F; color:#B68911;">
    <h1 style="font-weight: bolder;">Proyecto {{$prospecto->nombre}}</h1>
  </section>
  <!-- Main content -->
  <section class="content" id="content">
    <div class="row">
      <div class="col-lg-12">
        <div class="panel ">
          <div class="panel-heading" style="background-color:#12160F; color:#B68911;">
            <h3 class="panel-title">Ver Proyecto</h3>
          </div>
          <div class="panel-body">
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label class="control-label">Cliente</label>
                  <span class="form-control">{{$prospecto->cliente->nombre}}</span>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label class="control-label">Nombre de Proyecto</label>
                  <span class="form-control">{{$prospecto->nombre}}</span>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label class="control-label">Descripción</label>
                  <span class="form-control" style="min-height:68px;">{{$prospecto->descripcion}}</span>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12">
                <h4>Actividades</h4>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="table-responsive">
                  <table class="table table-bordred">
                    <thead>
                      <tr style="background-color:#12160F; color:#B68911;">
                        <th class="color_text">Fecha</th>
                        <th class="color_text">Tipo</th>
                        <th class="color_text">Productos Ofrecidos</th>
                        <th class="color_text">Descripción</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($prospecto->actividades as $actividad)
                      <tr>
                        <td>{{$actividad->fecha_formated}}</td>
                        <td>{{$actividad->tipo->nombre}}</td>
                        <td>
                          @foreach($actividad->productos_ofrecidos as $index => $ofrecido)
                          <span>{{$index+1}}.- {{$ofrecido->nombre}}</span><br />
                          @endforeach
                        </td>
                        @if($actividad->tipo->id==4) <!-- Cotización enviada -->
                        <td>
                          <a class="btn btn-warning" title="PDF" href="{{$actividad->descripcion}}" target="_blank">
                            <i class="far fa-file-pdf"></i>
                          </a>
                        </td>
                        @else
                        <td>{{$actividad->descripcion}}</td>
                        @endif
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12 text-right">
                <a href="{{route('prospectos.index')}}" style="margin-top:25px; color:#000; background-color:#B3B3B3;" class="btn btn-default">
                  Regresar
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </section>
  <!-- /.content -->
@stop

{{-- footer_scripts --}}
@section('footer_scripts')
@stop
