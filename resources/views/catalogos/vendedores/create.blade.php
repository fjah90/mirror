@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Nuevo Diseñador | @parent
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
  <section class="content-header" style="background-color:#12160F; color:#B68911;">
    <h1>Diseñadores</h1>
  </section>
  <!-- Main content -->
  <section class="content" id="content">
    <div class="row">
      <div class="col-lg-12">
        <div class="panel ">
          <div class="panel-heading" style="background-color:#12160F; color:#B68911;">
            <h3 class="panel-title">Nuevo Diseñador</h3>
          </div>
          <div class="panel-body">
            <form class="" @submit.prevent="guardar()">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label">Nombre<strong style="color: grey"> *</strong></label>
                    <input type="text" class="form-control" name="nombre" v-model="vendedor.nombre" required />
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label">Email<strong style="color: grey"> *</strong></label>
                    <input type="text" class="form-control" name="email" v-model="vendedor.email" required />
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label">Presupuesto Anual<strong style="color: grey"> *</strong></label>
                    <input type="number" class="form-control" name="presupuesto_anual" v-model="vendedor.presupuesto_anual" required />
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label">% Comision<strong style="color: grey"> *</strong></label>
                    <input type="number" class="form-control" name="comision_base" v-model="vendedor.comision_base" required />
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label">¿Cuándo se realizará el pago de la comisión?<strong style="color: grey"> *</strong></label>
                    <input type="text" class="form-control" name="pago_comision" v-model="vendedor.pago_comision" required />
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label">Color en hexadecimal (para los eventos)</label>
                    <input type="text" class="form-control" name="color" v-model="vendedor.color" required />
                  </div>
                </div>
              </div>
              <div class="row" style="margin-top:25px;">
                <div class="col-md-12 text-right">
                  <a class="btn btn-default" href="{{route('vendedores.index')}}" style="margin-right:20px; color:#000; background-color:#B3B3B3">
                    Regresar
                  </a>
                  <button type="submit" class="btn btn-primary" :disabled="cargando">
                    <i class="fas fa-save"></i>
                    Guardar Diseñador
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
        vendedor:{
            nombre: '',
            presupuesto_anual: '',
            comision_base: '',
            pago_comision: '',
            email:'',
            color:'',
        },
        cargando: false,
    },
    methods: {
      guardar(){
        this.cargando = true;
        axios.post('/vendedores', this.vendedor)
        .then(({data}) => {
          this.cargando = false;
          swal({
            title: "Vendedor Guardado",
            text: "",
            type: "success"
          }).then(()=>{
            window.location = "/vendedores";
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
