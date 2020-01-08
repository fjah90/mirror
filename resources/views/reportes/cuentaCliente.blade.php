@extends('layouts/default')

{{-- Page title --}}
@section('title')
Reportes | @parent
@stop

@section('content')
<section class="content-header">
    <h1>Cuenta Cliente </h1>
</section>
<section class="content" id="content">
    <div class="row">
        <div class="col-md-4 col-md-offset-8 form-horizontal">
          <div class="form-group p-10">
              <label class="col-md-3 control-label" for="example-select">Datos de: </label>
              <div class="col-md-9">
                <select name="" id="selectCliente" class="form-control" @change="cargar()" v-model="clienteCargado">
                  @foreach($data['clientes'] as $cliente)
                    <option value="{{$cliente->id}}">{{$cliente->nombre}}</option>
                  @endforeach
                </select>
              </div>
          </div>
        </div>
    </div>

    <div class="row" v-for="proyecto in data.proyectos">
        <div class="col-sm-12">
          <div class="panel product-details">
            <div class="panel-heading">
              <h3 class="panel-title"> @{{proyecto[0].proyecto}} </h3>
            </div>
            <div class="panel-body">
              <div class="row">
                <div class="col-sm-12">
                  <div class="table-responsive">
                    <table class="table table-striped text-center" id="tablaActividades">
                      <thead>
                        <tr>
                          <th class="text-center">Fecha</th>
                          <th class="text-center"><strong>Numero Cotizacion</strong></th>
                          <th class="text-center"><strong>Fecha Aprobacion</strong></th>
                          <th class="text-center"><strong>Moneda</strong></th>
                          <th class="text-center"><strong>Monto</strong></th>
                          <th class="text-center"><strong>Facturado</strong></th>
                          <th class="text-center"><strong>Por Facturar</strong></th>
                          <th class="text-center"><strong>Pagado</strong></th>
                          <th class="text-center"><strong>Pendiente</strong></th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="(row, index) in proyecto">
                          <td>@{{row.cotizacionFecha | date}}</td>
                          <td>@{{row.cotizacion_id}}</td>
                          <td>@{{row.aprobadoEn | date}}</td>
                          <td>@{{row.moneda}}</td>
                          <td>@{{row.total | formatoMoneda}}</td>
                          <td>@{{row.facturado | formatoMoneda}}</td>
                          <td>@{{(row.total-row.facturado) | formatoMoneda}}</td>
                          <td>@{{row.pagado | formatoMoneda}}</td>
                          <td>@{{row.pendiente | formatoMoneda}}</td>     
                        </tr>
                        <tfoot>
                            <tr>
                                <th colspan="4"  style="text-align:right">Total: </th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                            <tr>
                                <th colspan="4"  style="text-align:right">Total: </th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </tfoot>
                      </tbody>
                    </table>
                  </div>
                </div>
                <div class="col-sm-12 p-0">
                  <span id="product_status"></span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
</section>    
@stop
@section('footer_scripts')
<script>
  const app = new Vue({
    el: '#content',
    data: {
      data: {!! json_encode($data) !!},
      clienteCargado:'',
      locale: localeES,
    },
    filters:{
    formatoMoneda(numero){
      return accounting.formatMoney(numero, "$", 2);
    },
    formatoCurrency(valor){
      return valor=='Dolares'?'USD':'MXN';
    },
    date(value){
  			return moment(value, 'YYYY-MM-DD  hh:mm:ss').format('DD/MM/YYYY');
      },
  },
    methods: {
      dateParser(value){
  			return moment(value, 'DD/MM/YYYY').toDate().getTime();
      },
      cargar(){
        axios.get('/reportes/cuentaCliente', {params:{id: this.clienteCargado}})
        .then(({data}) => {
          console.log(data)
          this.data=data.data;
          swal({
            title: "Exito",
            text: "Datos Cargados",
            type: "success"
          }).then(()=>{
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
    }})
</script>
@stop