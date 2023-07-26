@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Ver Nota {{ $nota->id }}| @parent
@stop

@section('header_styles')
    <style>
        .color_text {
            color: #B3B3B3;
        }
        .h-auto{
            height: auto !important;
        }
    </style>
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header" style="background-color:#12160F; color:#B68911;">
        <h1>Notas</h1>
    </section>
    <!-- Main content -->
    <section class="content" id="content">
        <div class="row">
            <div class="col-lg-12">
                <div class="panel ">
                    <div class="panel-heading" style="background-color:#12160F; color:#B68911;">
                        <h3 class="panel-title">Notas #{{ $nota->id }}</h3>
                    </div>
                    <div class="panel-body">
                        <form class="" @submit.prevent="guardar()">
                            <div class="form-group">
                                <label for="titulo">Título:</label>
                                <span class="form-control">{{ $nota->titulo }}</span>

                            </div>
                            <div class="form-group">
                                <label for="contenido">Contenido:</label>
                                <span class="form-control h-auto">{{ $nota->contenido }}</span>
                            </div>

                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <a class="btn btn-default" href="{{ route('notas.index') }}"
                                        style="margin-right: 20px; color:#000; background-color:#B3B3B3;">
                                        Regresar
                                    </a>
                                    {{-- <button type="submit" class="btn btn-dark" :disabled="cargando"
                                        style="background-color:#12160F; color:#B68911;">
                                        <i class="fas fa-save"></i>
                                        Actualizar Nota
                                    </button> --}}
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </section>
    <!-- /.content -->
@stop

{{-- footer_scripts --}}
@section('footer_scripts')

    <script>
        Vue.config.devtools = true;

        const app = new Vue({
            el: '#content',
            data: {
                nota: {
                    titulo: '{{ $nota->titulo ?? '' }}',
                    contenido: '{{ $nota->contenido ?? '' }}',
                },
                cargando: false,
            },
            mounted() {

            },
            methods: {
                guardar() {
                    var formData = objectToFormData(this.nota, {
                        indices: true
                    });

                    this.cargando = true;
                    axios.put('/notas/{{ $nota->id }}', formData, {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        })
                        .then(({
                            data
                        }) => {
                            this.cargando = false;
                            swal({
                                title: "Nota Actualizado",
                                text: "",
                                type: "success"
                            }).then(() => {
                                window.location = "/notas";
                            });
                        })
                        .catch(({
                            response
                        }) => {
                            console.error(response);
                            this.cargando = false;
                            swal({
                                title: "Error",
                                text: response.data.message ||
                                    "Ocurrio un error inesperado, intente mas tarde",
                                type: "error"
                            });
                        });
                },
            }
        });
    </script>
@stop
