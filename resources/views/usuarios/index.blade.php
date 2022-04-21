@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Usuarios | @parent
@stop

@section('header_styles')

<!-- <style></style> -->
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1 style="font-weight: bolder;">Usuarios</h1>
    </section>
    <!-- Main content -->
    <section class="content" id="content">
        <div class="row">
            <div class="col-lg-12">
              <div class="panel">
                <div class="panel-heading">
                  <h3 class="panel-title text-right">
                    <span class="pull-left p-10">Lista de Usuarios</span>
                    <a href="{{route('usuarios.create')}}" class="btn btn-primary" style="color: #fff;">
                      <i class="fa fa-plus"></i> Nuevo Usuario
                    </a>
                  </h3>
                </div>
                <div class="panel-body">
                  <div class="table-responsive">
                    <table id="tabla" class="table table-bordred" style="width:100%;">
                      <thead>
                        <tr style="background-color:#f5bf4c">
                          <th>#</th>
                          <th>Tipo</th>
                          <th>Nombre</th>
                          <th>Email</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($usuarios as $index => $usuario)
                        <tr>
                          <td>{{$index+1}}</td>
                          <td>{{$usuario->tipo}}</td>
                          <td>{{$usuario->name}}</td>
                          <td>{{$usuario->email}}</td>
                          <td class="text-right">
                            <a class="btn btn-xs btn-info" title="Ver"
                              href="{{route('usuarios.show', ['usuario'=>$usuario->id])}}">
                              <i class="far fa-eye"></i>
                            </a>
                            <a class="btn btn-xs btn-success" title="Editar"
                              href="{{route('usuarios.edit', ['usuario'=>$usuario->id])}}">
                              <i class="fas fa-pencil-alt"></i>
                            </a>
                            @if($usuario->status == 'ACTIVO')
                              <a href="{{ route('usuarios.desactivar', ['usuarios' => $usuario->id]) }}" class="btn btn-xs label-danger float-left" data-toggle="tooltip" data-placement="top" title="Desactivar">
                                  <i class="fas fa-times"></i>
                              </a>
                            @else
                              <a href="{{ route('usuarios.activar', ['usuarios' => $usuario->id]) }}" class="btn btn-xs label-success float-left" data-toggle="tooltip" data-placement="top" title="Activar">
                                  <i class="fas fa-star"></i>
                              </a>
                            @endif
                          </td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
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

<!-- <script>
const app = new Vue({
    el: '#content',
    data: {},
    mounted(){
      this.dataTable = $("#tabla").DataTable({
        dom: 'lfrtip',
      });
    },
    methods: {
      algo(){console.log('hola');}
    }
});
</script> -->
@stop
