@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Editar Unidad Medida | @parent
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
    <h1>Unidades de Medida</h1>
  </section>
  <!-- Main content -->
  <section class="content" id="content">
    <div class="row">
      <div class="col-lg-12">
        <div class="panel ">
          <div class="panel-heading" style="background-color:#12160F; color:#B68911;">
            <h3 class="panel-title">Editar Unidad de Medida</h3>
          </div>
          <div class="panel-body">
            <form class="" @submit.prevent="guardar()">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label">Simbolo</label>
                    <input type="text" class="form-control" name="simbolo" v-model="unidad.simbolo" required />
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label">Nombre</label>
                    <input type="text" class="form-control" name="nombre" v-model="unidad.nombre" />
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label">Simbolo Ingles</label>
                    <input type="text" class="form-control" name="simbolo_ingles" v-model="unidad.simbolo_ingles" required />
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label">Nombre Ingles</label>
                    <input type="text" class="form-control" name="nombre_ingles" v-model="unidad.nombre_ingles" />
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12 text-right">
                  <a class="btn btn-default" href="{{route('unidadesMedida.index')}}" style="margin-right:20px; color:#000; background-color:#B3B3B3">
                    Regresar
                  </a>
                  <button type="submit" class="btn btn-dark" :disabled="cargando" style="background-color:#12160F; color:#B68911;">
                    <i class="fas fa-save"></i>
                    Actualizar Tipo
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-12">
        <div class="panel ">
          <div class="panel-heading" style="background-color:#12160F; color:#B68911;">
            <h3 class="panel-title">Conversiones de la Unidad</h3>
          </div>
          <div class="panel-body">
            <div class="row">
              <div class="col-md-12">
                <div class="table-responsive">
                  <table class="table">
                    <thead>
                      <tr>
                        <th>Unidad</th>
                        <th>Factor de Conversion</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(conversion, index) in unidad.conversiones">
                        <td>@{{conversion.unidad_conversion_simbolo}} - @{{conversion.unidad_conversion_nombre}}</td>
                        <td>@{{conversion.factor_conversion}}</td>
                        <td class="text-right">
                          <button class="btn btn-xs btn-success" data-toggle="tooltip" title="Editar"
                            @click="editarConversion(conversion, index)">
                            <i class="fas fa-pencil-alt"></i>
                          </button>
                          <button class="btn btn-xs btn-danger" data-toggle="tooltip" title="Borrar"
                            @click="borrarConversion(index)">
                            <i class="fas fa-times"></i>
                          </button>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <hr />
            <form class="" @submit.prevent="agregarConversion()">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label">Unidad de Conversion</label>
                    <select class="form-control" name="unidad_conversion"
                      v-model="conversion.unidad_conversion_id" required>
                      <option v-for="unidad in unidades" :value="unidad.id">@{{ unidad.simbolo }} - @{{ unidad.nombre }}</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label">Factor de Conversion</label>
                    <input type="number" min="0.0001" step="0.0001" class="form-control"
                      name="factor" v-model="conversion.factor_conversion" required
                    />
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12 text-right">
                  <button type="submit" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Conversion
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
      unidad: {!! json_encode($unidad) !!},
      unidades: {!! json_encode($unidades) !!},
      conversion: {
        unidad_conversion_id: '',
        unidad_conversion_simbolo: '',
        unidad_conversion_nombre: '',
        factor_conversion: ''
      },
      cargando: false,
    },
    methods: {
      agregarConversion(){
        var unidad = this.unidades.find(function(uni){
          return uni.id === this.conversion.unidad_conversion_id;
        }, this)

        this.conversion.unidad_conversion_simbolo = unidad.simbolo;
        this.conversion.unidad_conversion_nombre = unidad.nombre;
        this.unidad.conversiones.push(this.conversion);
        this.conversion = {
          unidad_conversion_id: '',
          unidad_conversion_simbolo: '',
          unidad_conversion_nombre: '',
          factor_conversion: ''
        };
      },
      editarConversion(conversion, index){
        this.conversion = conversion;
        this.unidad.conversiones.splice(index, 1);
      },
      borrarConversion(index){
        this.unidad.conversiones.splice(index, 1);
      },
      guardar(){
        this.cargando = true;
        axios.put('/unidadesMedida/{{ $unidad->id }}', this.unidad)
        .then(({data}) => {
          this.cargando = false;
          swal({
            title: "Unidad Actualizada",
            text: "",
            type: "success"
          }).then(()=>{
            window.location = "/unidadesMedida";
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
      },//fin guardar
    }
});
</script>
@stop
