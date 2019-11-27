@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Editar Tipo | @parent
@stop

@section('header_styles')
<style>
  table td:first-child span.fa-grip-vertical:hover {
    cursor: move;
  }
</style>
@stop

{{-- Page content --}}
@section('content')
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>Tipos Productos</h1>
  </section>
  <!-- Main content -->
  <section class="content" id="content">
    <div class="row">
      <div class="col-lg-12">
        <div class="panel ">
          <div class="panel-heading">
            <h3 class="panel-title">Editar Tipo</h3>
          </div>
          <div class="panel-body">
            <form class="" @submit.prevent="guardar()">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="control-label">Nombre</label>
                    <input type="text" class="form-control" name="nombre" v-model="categoria.nombre" required />
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="control-label">Name</label>
                    <input type="text" class="form-control" name="name" v-model="categoria.name" />
                  </div>
                </div>
              </div>
              <div class="row" style="margin-top:25px;" >
                <div class="col-md-12 text-right">
                  <a class="btn btn-default" href="{{route('categorias.index')}}" style="margin-right: 20px;">
                    Regresar
                  </a>
                  <button type="submit" class="btn btn-success" :disabled="cargando">
                    <i class="fas fa-save"></i>
                    Actualizar Tipo
                  </button>
                </div>
              </div>
            </form>
            <div class="row">
              <div class="col-sm-12">
                <h4>Agregar Descripciones</h4>
                <hr />
              </div>
            </div>
            <form class="" @submit.prevent="agregarDescripcion()">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="control-label">Nombre</label>
                    <input type="text" class="form-control" name="nombre" v-model="descripcion.nombre" />
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="control-label">Name</label>
                    <input type="text" class="form-control" name="name" v-model="descripcion.name" />
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="control-label" style="cursor:pointer;"
                      @click="descripcion.no_alta_productos=!descripcion.no_alta_productos">
                      <i class="text-info far"
                        :class="(descripcion.no_alta_productos)?'fa-check-square':'fa-square'">
                      </i>
                      No Alta Productos
                    </label>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12 text-right">
                  <button style="margin-top:25px;" type="submit" class="btn btn-info">
                    <i class="fas fa-save"></i>
                    Agregar Descripcion
                  </button>
                </div>
              </div>
            </form>
            <div class="row">
              <div class="col-md-12">
                <div class="table-responsive">
                  <table id="tabla" class="table table-bordred">
                    <thead>
                      <tr>
                        <th>Orden</th>
                        <th>Nombre</th>
                        <th>Name</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
                </div>
              </div>
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

<script>
const app = new Vue({
    el: '#content',
    data: {
      categoria: {!! json_encode($categoria) !!},
      descripcion:{
        nombre: '',
        name: '',
        ordenamiento: 0,
        no_alta_productos: false
      },
      dataTable: {},
      cargando: false,
    },
    mounted(){
      this.dataTable = $("#tabla").DataTable({
        data: [],
        searching: false,
        info: false,
        columnDefs: [
          { "orderable": true, "targets": 0 },
          { "orderable": false, "targets": '_all' }
        ],
        processing: false,
        paging: false,
        lengthChange: false,
        rowReorder: {
          selector: 'td:first-child span.fa-grip-vertical',
          snapX: true
        }
      });

      var vueInstance = this;
      //handler para reordenamiento
      this.dataTable.on( 'row-reorder', function ( e, diff, edit ) {
        // console.log(diff);
        // console.log(edit);
        var i = 0, j = diff.length;
        var nuevo_ordenamiento = 0;
        var indice_descripcion
        for (; i<j; i++) {
          nuevo_ordenamiento = diff[i].newPosition + 1; //+1 Por que empieza en 1
          //console.log(edit.nodes[i].cells[3].childNodes[0]); //Boton
          indice_descripcion = $(edit.nodes[i].cells[3].childNodes[0]).data('index');
          vueInstance.categoria.descripciones[indice_descripcion].actualizar = true;
          vueInstance.categoria.descripciones[indice_descripcion].ordenamiento = nuevo_ordenamiento;
        }
      });

      //handler para botones de editar y borrar
      $("#tabla")
        .on('click', 'tr button.btn-success', function(){
          var index = $(this).data('index');
          vueInstance.editarDescripcion(vueInstance.categoria.descripciones[index], index);
        })
        .on('click', 'button.btn-danger', function(){
          var index = $(this).data('index');
          vueInstance.borrarDescripcion(vueInstance.categoria.descripciones[index], index);
        });

      this.resetDataTables();
    },
    methods: {
      resetDataTables(){
        var rows = [], row = [];
        this.categoria.descripciones.forEach(function(descripcion, index){
          if(descripcion.borrar==true) return true;
          row = [
            '<span class="fas fa-grip-vertical"></span> '+descripcion.ordenamiento,
            descripcion.nombre,
            descripcion.name
          ];
          row.push([
            '<button class="btn btn-xs btn-success" title="Editar" data-index="'+index+'">',
              '<i class="fas fa-pencil-alt"></i>',
            '</button>',
            '<button class="btn btn-xs btn-danger" title="Borrar" data-index="'+index+'">',
              '<i class="fas fa-times"></i>',
            '</button>'
          ].join(''));
          rows.push(row);
        });

        this.dataTable.clear();
        this.dataTable.rows.add(rows);
        this.dataTable.draw();
      },
      cuentaDescripcionesNoBorradas(){
        var i = 0;
        this.categoria.descripciones.forEach(function(descripcion){
          if(descripcion.borrar!=true) i++;
        });
        return i;
      },
      agregarDescripcion(){
        if(this.descripcion.nombre=="" && this.descripcion.name==""){
          swal({
            title: "Atención",
            text: "Debe llenar el nombre o name",
            type: "warning"
          });
          return false;
        }

        if(this.descripcion.ordenamiento==0)
          this.descripcion.ordenamiento = this.cuentaDescripcionesNoBorradas()+1;

        this.categoria.descripciones.push(this.descripcion);
        this.resetDataTables();
        this.descripcion = {nombre: '', name: '', ordenamiento:0, no_alta_productos:false};
      },
      editarDescripcion(descripcion, index){
        descripcion.actualizar = true;
        this.descripcion = descripcion;
        this.categoria.descripciones.splice(index, 1);
        this.resetDataTables();
      },
      borrarDescripcion(descripcion, index, undefined){
        if(descripcion.id==undefined) this.categoria.descripciones.splice(index, 1);
        else descripcion.borrar = true;

        //restar 1 al ordenamiento de todas las descripciones con ordenamiento mayor
        //al de la descripcion borrada
        var ordenamiento = descripcion.ordenamiento;
        this.categoria.descripciones.forEach(function(descripcion){
          if(descripcion.ordenamiento>ordenamiento && descripcion.borrar==undefined){
            descripcion.actualizar = true;
            descripcion.ordenamiento--;
          }
        });

        this.resetDataTables();
      },
      guardar(){
        this.cargando = true;
        axios.put('/categorias/{{$categoria->id}}', this.categoria)
        .then(({data}) => {
          this.cargando = false;
          swal({
            title: "Tipo Actualizado",
            text: "",
            type: "success"
          }).then(()=>{
            window.location = "/categorias";
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
