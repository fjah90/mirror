@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Nuevo Agente Aduanal | @parent
@stop

@section('header_styles')
@stop

{{-- Page content --}}
@section('content')
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>Agentes Aduanales</h1>
  </section>
  <!-- Main content -->
  <section class="content" id="content">
    <div class="row">
      <div class="col-lg-12">
        <div class="panel ">
          <div class="panel-heading">
            <h3 class="panel-title">Nuevo Agente Aduanal</h3>
          </div>
          <div class="panel-body">
            <form class="" @submit.prevent="guardar()">
              <div class="row form-group">
                <div class="col-md-4">
                  <label class="control-label">Compañia</label>
                  <input type="text" class="form-control" name="compañia" v-model="agente.compañia" required />
                </div>
              </div>
              <div class="row form-group">
                <div class="col-md-12">
                  <label class="control-label">Direccion</label>
                  <input type="text" class="form-control" name="direccion" v-model="agente.direccion" required />
                </div>
              </div>
              <div class="row form-group">
                <div class="col-md-4">
                  <label class="control-label">Contacto</label>
                  <input type="text" class="form-control" name="contacto" v-model="agente.contacto" required />
                </div>
                <div class="col-md-4">
                  <label class="control-label">Telefono</label>
                  <input type="text" class="form-control" name="telefono" v-model="agente.telefono"
                    v-mask="['(###) ###-####','+#(###)###-####','+##(###)###-####']" required 
                  />
                </div>
                <div class="col-md-4">
                  <label class="control-label">Email</label>
                  <input type="email" class="form-control" name="email" v-model="agente.email" required />
                </div>
              </div>
              <div class="row">
                <div class="col-md-12 text-right">
                  <a href="{{route('agentesAduanales.index')}}" class="btn btn-default" style="margin-right: 20px;">
                    Regresar
                  </a>
                  <button type="submit" class="btn btn-primary" :disabled="cargando">
                    <i class="fas fa-save"></i>
                    Guardar Agente
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
const app = new Vue({
    el: '#content',
    data: {
      agente: {
        compañia: '',
        direccion: '',
        contacto: '',
        telefono: '',
        email: ''
      },
      cargando: false,
    },
    methods: {
      guardar(){
        this.cargando = true;
        axios.post('/agentesAduanales', this.agente)
        .then(({data}) => {
          this.cargando = false;
          swal({
            title: "Agente Guardado",
            text: "",
            type: "success"
          }).then(()=>{
            window.location = "/agentesAduanales";
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
