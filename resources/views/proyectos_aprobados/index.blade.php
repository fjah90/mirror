@extends('layouts/default')

{{-- Page title --}}
@section('title')
  Proyectos Aprobados | @parent
@stop

@section('header_styles')

<!-- <style></style> -->
@stop

{{-- Page content --}}
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>Proyectos Aprobados</h1>
</section>
<!-- Main content -->
<section class="content" id="content">
  <div class="row">
    <div class="col-lg-12">
      <div class="panel">
        <div class="panel-heading">
          <h3 class="panel-title">
            <div class="p-10">
              Lista de Proyectos
              @role('Administrador')
                de 
                <select class="form-control" @change="cargar()" v-model="usuarioCargado" style="width:auto;display:inline-block;">
                  <option value="Todos">Todos</option>
                  @foreach($usuarios as $usuario)
                  <option value="{{$usuario->id}}">{{$usuario->name}}</option>
                  @endforeach
                </select>
              @endrole
            </div>
          </h3>
        </div>
        <div class="panel-body">
          <div class="table-responsive">
            <table id="tabla" class="table table-bordred" style="width:100%;"
              data-page-length="-1">
              <thead>
                <tr>
                  <th>Usuario</th>
                  <th>Cliente</th>
                  <th>Proyecto</th>
                  <th>Numero de Cotización</th>
                  <th>Fecha aprobación</th>
                  <th>Proveedores</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(proyecto,index) in proyectos">
                  <td>@{{proyecto.cliente.usuario_nombre}}</td>
                  <td>@{{proyecto.cliente_nombre}}</td>
                  <td>@{{proyecto.proyecto}}</td>
                  <td>@{{proyecto.cotizacion_id}}</td>
                  {{--<td>@{{proyecto.created_at|date}}</td>--}}
                  <td v-if="proyecto.cotizacion.cuenta_cobrar.fecha_comprobante !== undefined">@{{proyecto.cotizacion.cuenta_cobrar.fecha_comprobante|date}}</td>
                  <td v-if="proyecto.cotizacion.cuenta_cobrar === undefined ||  proyecto.cotizacion.cuenta_cobrar.fecha_comprobante === undefined">@{{proyecto.cotizacion.cuenta_cobrar.fecha_comprobante|date}}</td>

                  <td>
                    <span v-for="(proveedor, index) in proyecto.proveedores">
                      @{{index+1}}.- @{{proveedor}} <br/>
                    </span>
                  </td>
                  <td class="text-right">
                    <a class="btn btn-xs btn-info" title="Ver Cotización"
                      target="_blank" :href="proyecto.cotizacion.archivo">
                      <i class="far fa-eye"></i>
                    </a>
                    <a class="btn btn-xs btn-success" title="Ordenes Compra"
                      :href="'/proyectos-aprobados/'+proyecto.id+'/ordenes-compra'">
                      <i class="fas fa-file-invoice-dollar"></i>
                    </a>
                  </td>
                </tr>
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

{{-- footer_scripts --}}
@section('footer_scripts')
<script src="{{ URL::asset('js/plugins/date-time/datetime-moment.js') }}" ></script>
<script>
const app = new Vue({
    el: '#content',
    data: {
      proyectos: {!! json_encode($proyectos) !!},
      usuarioCargado: {{auth()->user()->id}},
      tabla: {}
    },
    mounted(){
      this.tabla = $("#tabla").DataTable({"order": [[ 4, "desc" ]]});
    },
      filters:{
      date(value){
  			return moment(value, 'YYYY-MM-DD  hh:mm:ss').format('YYYY/MM/DD');
      },
    },
    methods:{
      dateParser(value){
  			return moment(value, 'DD/MM/YYYY').toDate().getTime();
      },
      cargar(){
        axios.post('/proyectos-aprobados/listado', {id: this.usuarioCargado})
        .then(({data}) => {
          this.tabla.destroy();
          this.proyectos = data.proyectos;
          swal({
            title: "Exito",
            text: "Datos Cargados",
            type: "success"
          }).then(()=>{
            this.tabla = $("#tabla").DataTable({"order": [[4,"desc" ]]});
          });
        })
        .catch(({response}) => {
          console.error(response);
          swal({
            title: "Error",
            text: response.data.message || "Ocurrio un error inesperado, intente mas tarde",
            type: "error"
          });
        });
      },
    }
});
</script>
@stop
