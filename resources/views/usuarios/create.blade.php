@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Nuevo Usuario | @parent
@stop

@section('header_styles')

<style>
#content {overflow: visible;}
</style>
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Usuarios</h1>
    </section>
    <!-- Main content -->
    <section class="content" id="content">
      <div class="row">
        <div class="col-lg-12">
          <div class="panel ">
            <div class="panel-heading">
              <h3 class="panel-title">Nuevo Usuario</h3>
            </div>
            <div class="panel-body">
              <form class="" @submit.prevent="guardar()">
                <div class="row">
                  <div class="col-md-9">
                    <div class="row">
                      <div class="col-sm-6">
                        <div class="form-group">
                          <label class="control-label">Tipo</label>
                          <select name="tipo" v-model="usuario.tipo" class="form-control" required >
                            @foreach($roles as $rol)
                            <option value="{{$rol->name}}">{{$rol->name}}</option>
                            @endforeach
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-6">
                        <div class="form-group">
                          <label class="control-label">Nombre</label>
                          <input type="text" class="form-control" name="nombre" v-model="usuario.nombre" required />
                        </div>
                      </div>
                      <div class="col-sm-6">
                        <div class="form-group">
                          <label class="control-label">Email</label>
                          <input type="email" class="form-control" name="email" v-model="usuario.email" required />
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-6">
                        <div class="form-group">
                          <label class="control-label">Contaseña</label>
                          <input type="password" class="form-control" name="password" v-model="usuario.contraseña" required />
                        </div>
                      </div>
                      <div class="col-sm-6">
                        <div class="form-group">
                          <label class="control-label">Repetir Contraseña</label>
                          <input type="password" class="form-control" name="password_confirmation" v-model="usuario.contraseña_confirmation" required />
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="control-label" style="display:block;">Firma</label>
                          <div class="kv-avatar">
                            <div class="file-loading">
                              <input id="firma" name="firma" type="file" ref="firma" @change="fijarArchivo('firma')" />
                            </div>
                          </div>
                          <div id="firma-errors"></div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="row">
                      <div class="col-sm-12 text-right">
                        <button style="margin-top:25px;" type="submit" class="btn btn-primary" :disabled="cargando">
                          <i class="fa fa-save"></i>
                          Guardar Usuario
                        </button>
                      </div>
                    </div>
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
const app = new Vue({
    el: '#content',
    data: {
      usuario: {
        tipo: '',
        nombre: '',
        email: '',
        contraseña: '',
        contraseña_confirmation: '',
        firma: ''
      },
      cargando: false,
    },
    mounted(){
      $("#firma").fileinput({
        language: 'es',
        overwriteInitial: true,
        maxFileSize: 5000,
        showClose: false,
        showCaption: false,
        showBrowse: false,
        browseOnZoneClick: true,
        removeLabel: '',
        removeIcon: '<i class="glyphicon glyphicon-remove"></i>',
        removeTitle: 'Quitar Foto',
        defaultPreviewContent: '<img src="{{asset('images/camara.png')}}" alt="foto"><h6 class="text-muted">Click para seleccionar</h6>',
        layoutTemplates: {main2: '{preview} {remove} {browse}'},
        allowedFileExtensions: ["jpg", "jpeg", "png"],
        elErrorContainer: '#firma-errors'
      });
    },
    methods: {
      fijarArchivo(campo){
        this.usuario[campo] = this.$refs[campo].files[0];
      },
      guardar(){
        this.cargando = true;
        var formData = objectToFormData(this.usuario);

        axios.post('/usuarios', formData, {
          headers: { 'Content-Type': 'multipart/form-data'}
        })
        .then(({data}) => {
          this.cargando = false;
          swal({
            title: "Usuario Guardado",
            text: "",
            type: "success"
          }).then(()=>{
            window.location = "/usuarios";
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
      },//fin cargarPresupuesto
    }
});
</script>
@stop