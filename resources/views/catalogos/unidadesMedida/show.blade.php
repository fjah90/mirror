@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Ver Unidad Medida | @parent
@stop

@section('header_styles')
@stop

{{-- Page content --}}
@section('content')
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>Unidades de Medida</h1>
  </section>
  <!-- Main content -->
  <section class="content" id="content">
    <div class="row">
      <div class="col-lg-12">
        <div class="panel ">
          <div class="panel-heading">
            <h3 class="panel-title">Ver Unidad de Medida</h3>
          </div>
          <div class="panel-body">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label">Simbolo</label>
                    <span class="form-control">{{ $unidad->simbolo }}</span>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label">Nombre</label>
                    <span class="form-control">{{ $unidad->nombre }}</span>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12 text-right">
                  <a href="{{ route('unidadesMedida.index') }}" class="btn btn-info">
                    Regresar
                  </a>
                </div>
              </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-12">
        <div class="panel ">
          <div class="panel-heading">
            <h3 class="panel-title">Conversiones de la Unidad</h3>
          </div>
          <div class="panel-body">
            <div class="row">
              <div class="col-md-12">
                <div class="table-responsive">
                  <table class="table">
                    <thead>
                      <tr>
                        <th>Unidad</th>
                        <th>Factor de Conversion</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($unidad->conversiones as $conversion)
                        <tr>
                          <td>{{$conversion->unidad_conversion_simbolo}} - {{$conversion->unidad_conversion_nombre}}</td>
                          <td>{{$conversion->factor_conversion}}</td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
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

{{-- <script>
</script> --}}
@stop
