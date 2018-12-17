@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Ver Prospecto | @parent
@stop

@section('header_styles')
@stop

{{-- Page content --}}
@section('content')
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>Prospectos</h1>
  </section>
  <!-- Main content -->
  <section class="content" id="content">
    <div class="row">
      <div class="col-lg-12">
        <div class="panel ">
          <div class="panel-heading">
            <h3 class="panel-title">Ver Prospecto</h3>
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
                      <tr>
                        <th>Fecha</th>
                        <th>Tipo</th>
                        <th>Productos Ofrecidos</th>
                        <th>Descripción</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($prospecto->actividades as $actividad)
                      <tr>
                        <td>{{$actividad->fecha_formated}}</td>
                        <td>{{$actividad->tipo->nombre}}</td>
                        <td>
                          @foreach($actividad->productos_ofrecidos as $index => $ofrecido)
                          <span>{{$index}}.- {{$ofrecido->composicion}}</span><br />
                          @endforeach
                        </td>
                        <td>{{$actividad->descripcion}}</td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12 text-right">
                <a href="{{route('prospectos.index')}}" style="margin-top:25px;" class="btn btn-info">
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
