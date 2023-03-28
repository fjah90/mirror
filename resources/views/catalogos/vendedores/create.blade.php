@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Nuevo Vendedor | @parent
@stop

@section('header_styles')
@stop

{{-- Page content --}}
@section('content')
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>Vendedores</h1>
  </section>
  <!-- Main content -->
  <section class="content" id="content">
    <div class="row">
      <div class="col-lg-12">
        <div class="panel ">
          <div class="panel-heading">
            <h3 class="panel-title">Nuevo Vendedor</h3>
          </div>
          <div class="panel-body">
            <form class="" @submit.prevent="guardar()">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label">Nombre</label>
                    <input type="text" class="form-control" name="nombre" v-model="vendedor.nombre" required />
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label">Presupuesto Anual</label>
                    <input type="number" class="form-control" name="presupuesto_anual" v-model="vendedor.presupuesto_anual" required />
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label">% Comision</label>
                    <input type="number" class="form-control" name="comision_base" v-model="vendedor.comision_base" required />
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label">¿Cuándo se realizará el pago de la comisión?</label>
                    <input type="text" class="form-control" name="pago_comision" v-model="vendedor.pago_comision" required />
                  </div>
                </div>
              </div>
              <div class="row" style="margin-top:25px;">
                <div class="col-md-12 text-right">
                  <a class="btn btn-default" href="{{route('vendedores.index')}}" style="margin-right:20px;">
                    Regresar
                  </a>
                  <button type="submit" class="btn btn-primary" :disabled="cargando">
                    <i class="fas fa-save"></i>
                    Guardar Vendedor
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
