@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Editar Nota | @parent
@stop

@section('header_styles')
    <style>
        .color_text {
            color: #B3B3B3;
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
                        <h3 class="panel-title">Editar Notas</h3>
                    </div>
                    <div class="panel-body">
                        <form class="" @submit.prevent="guardar()">
                            <div class="form-group">
                                <label for="titulo">Título:</label>
                                <input type="text" class="form-control" id="titulo" name="titulo" v-model="nota.titulo"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="contenido">Contenido:</label>
                                <textarea class="form-control" id="contenido" name="contenido" v-model="nota.contenido" rows="10"></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <a class="btn btn-default" href="{{ route('notas.index') }}"
                                        style="margin-right: 20px; color:#000; background-color:#B3B3B3;">
                                        Regresar
                                    </a>
                                    <button type="submit" class="btn btn-dark" :disabled="cargando"
                                        style="background-color:#12160F; color:#B68911;">
                                        <i class="fas fa-save"></i>
                                        Actualizar Nota
                                    </button>
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
                    titulo: '{{ $nota->titulo }}',
                    contenido: '{{ $nota->contenido }}',
                },
                cargando: false,
            },
            methods: {
                guardar() {
                    let formData = objectToFormData(this.nota, {
                        indices: true
                    });
                    console.log(formData.get('titulo'));
                    this.cargando = true;
                    axios.post('/notas/{{ $nota->id }}', formData, {
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
