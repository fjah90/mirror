@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Editar Usuario | @parent
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
              <h3 class="panel-title">Editar Usuario</h3>
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
                          <input type="password" class="form-control" name="password" v-model="usuario.contraseña" />
                        </div>
                      </div>
                      <div class="col-sm-6">
                        <div class="form-group">
                          <label class="control-label">Repetir Contraseña</label>
                          <input type="password" class="form-control" name="password_confirmation" v-model="usuario.contraseña_confirmation" />
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
                        <button style="margin-top:25px;" type="submit" class="btn btn-success" :disabled="cargando">
                          <i class="fa fa-save"></i>
                          Actualizar Usuario
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
        tipo: '{{$usuario->tipo}}',
        nombre: '{{$usuario->name}}',
        email: '{{$usuario->email}}',
        contraseña: '',
        contraseña_confirmation: '',
        firma_ori: '{{$usuario->firma}}',
        firma:''
      },
      cargando: false,
    },
    mounted(){
      if(this.usuario.firma_ori){
        var preview = '<img src="'+this.usuario.firma_ori+'" alt="logo"><h6 class="text-muted">Click para seleccionar</h6>';
      }
      else{
        var preview = '<img src="{{asset('images/camara.png')}}" alt="foto"><h6 class="text-muted">Click para seleccionar</h6>';
      }

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
        defaultPreviewContent: preview,
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

        axios.post('/usuarios/{{$usuario->id}}', formData, {
          headers: { 'Content-Type': 'multipart/form-data'}
        })
        .then(({data}) => {
          this.cargando = false;
          swal({
            title: "Usuario Actualizado",
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
