@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Notas | @parent
@stop

@section('header_styles')
    <style>
        .color_text {
            color: #B3B3B3;
        }

        .ellipse {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            line-height: 1.5;
            text-align: justify;
        }
    </style>
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header" style="background-color:#12160F; color:#B68911;">
        <h1>NOTAS</h1>
    </section>
    <!-- Main content -->
    <section class="content" id="content">
        <div class="row">
            <div class="col-lg-12">
                <div class="panel">
                    <div class="panel-heading" style="background-color:#12160F; color:#B68911;">
                        <h3 class="panel-title text-right">
                            <span class="pull-left p-10">Lista de Notas</span>

                            {{-- <button type="submit" class="btn btn-dark" style="background-color:#FFCE56; color:#12160F;">
                <a href="{{route('notas.index')}}" style="color:#000;">
                  <i class="fas fa-user-book"></i>ACTIVOS
                </a>
              </button> --}}
                            {{-- <button type="submit" class="btn btn-dark" style="background-color:#FFCE56; color:#12160F;">
                <a href="{{route('notas.inactivo')}}" style="color:#000;">
                  <i class="fas fa-user-book"></i>INACTIVOS
                </a>
              </button> --}}
                            {{-- <a href="{{route('notas.create2')}}" class="btn btn-warning" style="color: #000;">
              <i class="fas fa-plus"></i> Carga masiva
            </a> --}}
                            <a href="{{ route('notas.create') }}" class="btn btn-warning" style="color: #000;">
                                <i class="fas fa-plus"></i> Nueva Nota
                            </a>
                        </h3>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table id="tabla" class="table table-bordred" style="width:100%;" data-page-length="20">
                                <thead>
                                    <tr style="background-color:#12160F">
                                        <th class="color_text">#</th>
                                        <th class="color_text">Título</th>
                                        <th class="color_text">Contenido</th>
                                        <th style="min-width:70px;"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(nota, index) in notas">
                                        <td>@{{ index + 1 }}</td>
                                        <td>@{{ nota.titulo }}</td>
                                        <td>
                                            <p class="ellipse">
                                                @{{ nota.contenido }}
                                            </p>
                                        </td>
                                        <td class="text-right col-md-3">
                                            <a class="btn btn-xs btn-info" data-toggle="tooltip" title="Ver"
                                                :href="'/notas/' + nota.id">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a class="btn btn-xs btn-success" data-toggle="tooltip" title="Editar"
                                                :href="'/notas/' + nota.id + '/editar'">
                                                <i class="fas fa-pencil-alt"></i>
                                            </a>
                                            @hasrole('Administrador')
                                                <button class="btn btn-xs btn-danger" data-toggle="tooltip" title="Borrar"
                                                    @click="borrar(nota, index)">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            @endhasrole
                                        </td>
                                    </tr>
                                    {{-- @foreach ($notas as $nota)
                                        <tr>
                                            <td>{{ $nota->id }}</td>
                                            <td>{{ $nota->titulo }}</td>
                                            <td>{{ $nota->contenido }}</td>
                                            <td class="text-right col-md-3">
                                                <a class="btn btn-xs btn-info" data-toggle="tooltip" title="Ver"
                                                    :href="{{ route('notas.show', $nota->id) }}">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a class="btn btn-xs btn-success" data-toggle="tooltip" title="Editar"
                                                    :href="{{ route('notas.edit', $nota->id) }}">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                                                {{-- @hasrole('Administrador')
                                                    <button class="btn btn-xs btn-danger" data-toggle="tooltip" title="Borrar"
                                                        @click="borrar(nota, index)">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                @endhasrole --}
                                            </td>
                                        </tr>
                                    @endforeach --}}
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


@section('footer_scripts')
    <script>
        new Vue({
            el: '#content',
            data: {
                notas: {!! json_encode($notas) !!},
            },
            methods: {
                borrar(nota, index) {
                    swal({
                        title: 'Cuidado',
                        text: "Borrar esta nota '" + nota.titulo +
                            "' puede afectar cotizaciones, ¿Desea continuar?",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Si, Borrar',
                        cancelButtonText: 'No, Cancelar',
                    }).then((result) => {
                        if (result.value) {
                            axios.delete('/notas/' + nota.id, {})
                                .then(({
                                    data
                                }) => {
                                    this.notas.splice(index, 1);
                                    swal({
                                        title: "Exito",
                                        text: "La nota ha sido borrado",
                                        type: "success"
                                    });
                                })
                                .catch(({
                                    response
                                }) => {
                                    console.error(response);
                                    swal({
                                        title: "Error",
                                        text: response.data.message ||
                                            "Ocurrio un error inesperado, intente mas tarde",
                                        type: "error"
                                    });
                                });
                        } //if confirmacion
                    });
                },
            }
        });

        $(document).ready(function() {
            $('#tabla').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json"
                },
                "paging": true,
                "ordering": true,
                "info": true,
            });
        });
    </script>
@stop
