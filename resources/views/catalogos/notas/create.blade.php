@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Nuevo Nota | @parent
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
    <section class="content-header" style="background-color:#12160F; color:#caa678;">
        <h1>Notas</h1>
    </section>
    <!-- Main content -->
    <section class="content" id="content">
        <div class="row">
            <div class="col-lg-12">
                <div class="panel ">
                    <div class="panel-heading" style="background-color:#12160F; color:#caa678;">
                        <h3 class="panel-title">Nuevo Nota</h3>
                    </div>
                    <div class="panel-body">
                        <form @submit.prevent="guardar()" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="titulo">Título *</label>
                                <input type="text" class="form-control" id="titulo" name="titulo"
                                    v-model="nota.titulo" required>
                            </div>

                            <div class="form-group">
                                <label for="contenido">Contenido *</label>
                                <textarea class="form-control" id="contenido" name="contenido" v-model="nota.contenido" rows="5" required></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary">Crear nota</button>
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
        /* beautify ignore:start */
        const app = new Vue({
            el: '#content',
            data: {
              nota: {
                titulo: '',
                contenido: ''
              },
              cargando: false,

            },
            mounted(){

            },
            methods: {
              guardar(){
                var formData = objectToFormData(this.nota, {indices:true});
                console.log(formData)
                this.cargando = true;
                axios.post('/notas', formData, {
                  headers: { 'Content-Type': 'multipart/form-data'}
                })
                .then(({data}) => {
                  this.cargando = false;
                  swal({
                    title: "Nota Guardada",
                    text: "",
                    type: "success"
                  }).then(()=>{
                    if(this.is_iframe){
                      parent.postMessage({message:"OK", tipo:"nota", object: data.nota}, "*")
                      window.location = "/notas/crear";
                    }else{
                      window.location = "/notas";
                    }     
                  });
                })
                .catch(({response}) => {
                  console.error(response);
                  this.cargando = false;
                  swal({
                    title: "Error",
                    text: response.data.message || "Ocurrio un error inesperado, intente mas tarde",
                    type: "error"
                  });
                });
              },
            }
        });
        /* beautify ignore:end */
    </script>
@stop
