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
            <div class="p-10">
              Año  
                <select class="form-control" @change="cargar()" v-model="anio" style="width:auto;display:inline-block;">
                  <option value="Todos">Todos</option>
                  <option value="2019-12-31">2019</option>
                  <option value="2020-12-31">2020</option>
                  <option value="2021-12-31">2021</option>
                </select>
            </div>
          </h3>
        </div>
        <div class="panel-body">
          <div class="table-responsive">
            <table id="tabla" class="table table-bordred" style="width:100%;"
              data-page-length="-1">
              <thead>
                <tr>
                  <th># Cotización</th>
                  <th>Usuario</th>
                  <th>Cliente</th>
                  <th>Proyecto</th>
                  <th>Fecha aprobación</th>
                  <th>Proveedores</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(proyecto,index) in proyectos">
                  <td>@{{proyecto.cotizacion.numero}}</td>
                  <td>@{{proyecto.cotizacion.user.name}}</td>
                  <td>@{{proyecto.cliente_nombre}}</td>
                  <td>@{{proyecto.proyecto}}</td>
                  {{--<td>@{{proyecto.created_at|date}}</td>--}}
                  <td v-if="proyecto.cotizacion.cuenta_cobrar !== null && proyecto.cotizacion.cuenta_cobrar !== undefined && proyecto.cotizacion.cuenta_cobrar.fecha_comprobante !== undefined && proyecto.cotizacion.cuenta_cobrar.fecha_comprobante !== null">@{{proyecto.cotizacion.cuenta_cobrar.fecha_comprobante|date}}</td>
                  <td v-if="
                  proyecto.cotizacion.cuenta_cobrar === null ||
                  proyecto.cotizacion.cuenta_cobrar === undefined ||  proyecto.cotizacion.cuenta_cobrar.fecha_comprobante === undefined || proyecto.cotizacion.cuenta_cobrar.fecha_comprobante === null">@{{proyecto.created_at|date}}</td>

                  <td>
                    <span v-for="(orden, index) in proyecto.ordenes">
                      @{{index+1}}.- @{{orden.proveedor_empresa}} ,@{{orden.numero}} , @{{orden.status}}  <br/>
                    </span>
                  </td>
                  <td class="text-right">
                    <a class="btn btn-xs btn-info" title="Ver Cotización"
                      target="_blank" :href="proyecto.cotizacion.archivo">
                      <i class="far fa-file"></i>
                    </a>
                    
                    <a class="btn btn-xs btn-info" title="Ver Proyecto"
                      target="_blank" :href="'/proyectos-aprobados/'+proyecto.id+'/show'">
                      <i class="far fa-eye"></i>
                    </a>
                
                    <a class="btn btn-xs btn-success" title="Ordenes Compra"
                      :href="'/proyectos-aprobados/'+proyecto.id+'/ordenes-compra'">
                      <i class="fas fa-file-invoice-dollar"></i>
                    </a>
                    <button class="btn btn-xs btn-danger" title="Borrar" @click="borrar(proyecto, index)">
                      <i class="fas fa-times"></i>
                    </button>
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
      anio:'2021-12-31',
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
        axios.post('/proyectos-aprobados/listado', {id: this.usuarioCargado, anio:this.anio})
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
      borrar(proyecto, index){
        swal({
          title: 'Cuidado',
          text: "Desaprobar el proyecto "+proyecto.proyecto+"?",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Si, Borrar',
          cancelButtonText: 'No, Cancelar',
        }).then((result) => {
          if (result.value) {
            axios.delete('/proyectos-aprobados/'+proyecto.id, {})
            .then(({data}) => {
              this.proyectos.splice(index, 1);
              swal({
                title: "Exito",
                text: "El Proyecto ha sido desaprobado",
                type: "success"
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
          } //if confirmacion
        });
      },//fin borrar
    }
});
</script>
@stop
