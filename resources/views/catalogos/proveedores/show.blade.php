@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Ver Proveedor | @parent
@stop

@section('header_styles')
@stop

{{-- Page content --}}
@section('content')
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>Proveedores</h1>
  </section>
  <!-- Main content -->
  <section class="content" id="content">
    <div class="row">
      <div class="col-lg-12">
        <div class="panel ">
          <div class="panel-heading">
            <h3 class="panel-title">Ver Proveedor {{$proveedor->empresa}}</h3>
          </div>
          <div class="panel-body">
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label class="control-label">Nombre</label>
                  <span class="form-control">{{$proveedor->empresa}}</span>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label class="control-label">RFC</label>
                  <span class="form-control">{{$proveedor->rfc}}</span>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label class="control-label">Telefono</label>
                  <span class="form-control">{{$proveedor->telefono}}</span>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label class="control-label">Email</label>
                  <span class="form-control">{{$proveedor->email}}</span>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label class="control-label">Banco</label>
                  <span class="form-control">{{$proveedor->banco}}</span>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label class="control-label">Numero Cuenta</label>
                  <span class="form-control">{{$proveedor->numero_cuenta}}</span>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label class="control-label">Clave Interbancaria</label>
                  <span class="form-control">{{$proveedor->clave_interbancaria}}</span>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label class="control-label">Moneda</label>
                  <span class="form-control">{{$proveedor->moneda}}</span>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label class="control-label">Dias Credito</label>
                  <span class="form-control">{{$proveedor->dias_credito}}</span>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label class="control-label">Dirección</label>
                  <span class="form-control">{{$proveedor->direccion}}</span>
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
          <div class="panel-heading">
            <h3 class="panel-title">Contactos del Proveedor</h3>
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
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($proveedor->contactos as $contacto)
                      <tr>
                        <td>{{$contacto->nombre}}</td>
                        <td>{{$contacto->cargo}}</td>
                        <td>{{$contacto->email}}</td>
                        <td>{{$contacto->telefono}}</td>
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
        <a class="btn btn-info" href="{{route('proveedores.index')}}">
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
