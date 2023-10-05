@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Ver Cliente | @parent
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
  <section class="content-header" style="background-color:#12160F; color:#caa678;">
    <h1>Clientes</h1>
  </section>
  <!-- Main content -->
  <section class="content" id="content">
    <div class="row">
      <div class="col-lg-12">
        <div class="panel ">
          <div class="panel-heading" style="background-color:#12160F; color:#caa678;">
            <h3 class="panel-title">Ver Cliente {{$cliente->nombre}}</h3>
          </div>
          <div class="panel-body">
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label class="control-label">Usuario</label>
                  <span class="form-control">{{$cliente->usuario_nombre}}</span>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label class="control-label">Tipo</label>
                  <span class="form-control">{{$cliente->tipo->nombre}}</span>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label class="control-label">Nombre</label>
                  <span class="form-control">{{$cliente->nombre}}</span>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label class="control-label">RFC</label>
                  <span class="form-control">{{$cliente->rfc}}</span>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label class="control-label">Categoría de Cliente</label>
                  <span class="form-control">{{$cliente->categoria->nombre}}</span>
                </div>
              </div>
              <div class="col-md-8">
                <div class="form-group">
                  <label class="control-label">Preferencias y necesidades</label>
                  <span class="form-control">{{$cliente->preferencias}}</span>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label class="control-label">Razon Social</label>
                  <span class="form-control">{{$cliente->razon_social}}</span>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label class="control-label">Telefono</label>
                  <span class="form-control">{{$cliente->telefono}}</span>
                </div>
              </div>
              <div class="col-md-8">
                <div class="form-group">
                  <label class="control-label">Email</label>
                  <span class="form-control">{{$cliente->email}}</span>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label class="control-label">Dirección</label>
                  <span class="form-control">{{$cliente->direccion}}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-12">
        <div class="panel ">
          <div class="panel-heading" style="background-color:#12160F; color:#caa678;">
            <h3 class="panel-title">Contactos del Cliente</h3>
          </div>
          <div class="panel-body">
            <div class="row">
              <div class="col-md-12">
                <div class="table-responsive">
                  <table class="table">
                    <thead>
                      <tr>
                        <th>Nombre</th>
                        <th>Cargo</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Teléfono 2</th>
                        <th>Fax</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($cliente->contactos as $contacto)
                      <tr>
                        <td>{{$contacto->nombre}}</td>
                        <td>{{$contacto->cargo}}</td>
                        <td>{{$contacto->email}}</td>
                        <td>{{$contacto->tipo_telefono}} {{$contacto->telefono}} Ext. {{$contacto->extencion_telefono}}</td>
                        <td>{{$contacto->tipo_telefono2}} {{$contacto->telefono2}} Ext. {{$contacto->extencion_telefono2}}</td>
                        <td>{{$contacto->fax}}</td>
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

    <div class="row">
      <div class="col-md-12 text-right">
        <a class="btn btn-default" href="{{route('clientes.index')}}" style="color:#000; background-color:#B3B3B3">
          Regresar
        </a>
      </div>
    </div>
  </section>
  <!-- /.content -->
@stop

{{-- footer_scripts --}}
@section('footer_scripts')
@stop
