@extends('layouts/default')

{{-- Page title --}}
@section('title')
  Cuentas por Cobrar | @parent
@stop

@section('header_styles')

<!-- <style></style> -->
@stop

{{-- Page content --}}
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1 style="font-weight: bolder;">Cuentas por Cobrar</h1>
</section>
<!-- Main content -->
<section class="content" id="content">
  <div class="row">
    <div class="col-lg-12">
      <div class="panel">
        <div class="panel-heading">
          <h3 class="panel-title">
            <span class="p-10">Lista de Cuentas</span>

            <div class="p-10">
              @role('Administrador')
              Ejecutivo  
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
                  <option value="2022-12-31">2022</option>
                  <option value="2023-12-31">2023</option>
                </select>
            </div>
          </h3>
        </div>
        <div class="panel-body">
          <div class="table-responsive">
            <table class="table table-bordred" id="tabla">
              <thead>
                <tr style="background-color:#f54c82">
                  <th>#</th>
                  <th># Cotizacion</th>
                  <th>Ejecutivo</th>
                  <th>Cliente</th>
                  <th>Proyecto</th>
                  <th>Condiciones Pago</th>
                  <th>Moneda</th>
                  <th>Total</th>
                  <th>Facturado</th>
                  <th>Pagado</th>
                  <th>Pendiente</th>
                  <th style="min-width:70px;"></th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(cuenta, index) in cuentas">
                  <td>@{{index+1}}</td>
                  <td>@{{cuenta.cotizacion.numero}}</td>
                  <td>@{{cuenta.cotizacion.user.name}}</td>
                  <td>@{{cuenta.cliente}}</td>
                  <td>@{{cuenta.proyecto}}</td>
                  <td>@{{cuenta.condiciones}}</td>
                  <td v-bind:style= "[cuenta.moneda == 'Dolares' ? {'color':'#266e07'} : {'color':'#150a9b'}]">@{{cuenta.moneda}}</p></td>
                  <td v-bind:style= "[cuenta.moneda == 'Dolares' ? {'color':'#266e07'} : {'color':'#150a9b'}]">@{{cuenta.total | formatoMoneda}}</td>
                  <td v-bind:style= "[cuenta.moneda == 'Dolares' ? {'color':'#266e07'} : {'color':'#150a9b'}]">@{{cuenta.facturado | formatoMoneda}}</td>
                  <td v-bind:style= "[cuenta.moneda == 'Dolares' ? {'color':'#266e07'} : {'color':'#150a9b'}]">@{{cuenta.pagado | formatoMoneda}}</td>
                  <td v-bind:style= "[cuenta.moneda == 'Dolares' ? {'color':'#266e07'} : {'color':'#150a9b'}]">@{{cuenta.pendiente | formatoMoneda}}</td>
                  <td class="text-right">
                    <a class="btn btn-xs btn-info" data-toggle="tooltip" title="Ver"
                      :href="'/cuentas-cobrar/'+cuenta.id">
                      <i class="far fa-eye"></i>
                    </a>
                    <a class="btn btn-xs btn-success" data-toggle="tooltip" title="Facturas"
                      :href="'/cuentas-cobrar/'+cuenta.id+'/editar'">
                      <i class="fas fa-file-invoice-dollar"></i>
                    </a>
                  </td>
                </tr>
              </tbody>
              <tfoot>
                  <tr>
                      <th colspan="11" style="text-align:right">Total MXN:</th>
                      <th></th>
                  </tr>
                  <tr>
                      <th colspan="11" style="text-align:right">Total USD:</th>
                      <th></th>
                  </tr>
              </tfoot>
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
<script>
const app = new Vue({
    el: '#content',
    data: {
      usuarioCargado: 'Todos',
      anio:'2023-12-31',
      cuentas: {!! json_encode($cuentas) !!},
    },
    filters: {
      formatoMoneda(numero){
        return accounting.formatMoney(numero, "$", 2);
      }
    },
    mounted(){
      this.tabla = $("#tabla").DataTable({
        "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;

            var formato = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
            //datos de la tabla con filtros aplicados
            var datos= api.columns([7,6], {search: 'applied'}).data();
            var totalMxn = 0;
            var totalUsd = 0;
            //suma de montos
            datos[0].forEach(function(element, index){
                if(datos[1][index]=="Dolares"){
                    totalUsd+=formato(element)
                }else{
                    totalMxn+=formato(element)
                }
            });
 
            // Actualizar
            var nCells = row.getElementsByTagName('th');
            nCells[1].innerHTML = accounting.formatMoney(totalMxn, "$", 2);

            var secondRow = $(row).next()[0]; 
            var nCells = secondRow.getElementsByTagName('th');
            nCells[1].innerHTML = accounting.formatMoney(totalUsd, "$", 2);
        }

      });
    },
    methods: {
      
      cargar(){
        axios.post('/cuentas-cobrar/listado', {id: this.usuarioCargado,anio:this.anio})
        .then(({data}) => {
          this.tabla.destroy();
          this.cuentas = data.cuentas;
          swal({
            title: "Exito",
            text: "Datos Cargados",
            type: "success"
          }).then(()=>{
            this.tabla = $("#tabla").DataTable();
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
      }
    }
});
</script>
@stop
