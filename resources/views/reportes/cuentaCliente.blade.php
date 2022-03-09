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
                <div class="marg025 btn-group">
                  <button class="btn btn-primary" v-on:click="pdf">
                      PDF
                  </button>
                </div>
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
                                <th colspan="4"  style="text-align:right">Total MXN: </th>
                                <th class="text-center">@{{proyecto.totalMxn.monto | formatoMoneda}} </th>
                                <th class="text-center">@{{proyecto.totalMxn.facturado | formatoMoneda}}</th>
                                <th class="text-center">@{{proyecto.totalMxn.porFacturar | formatoMoneda}}</th>
                                <th class="text-center">@{{proyecto.totalMxn.pagado | formatoMoneda}}</th>
                                <th class="text-center">@{{proyecto.totalMxn.pendiente | formatoMoneda}}</th>
                            </tr>
                            <tr>
                                <th colspan="4"  style="text-align:right">Total USD: </th>
                                <th class="text-center">@{{proyecto.totalDolares.monto | formatoMoneda}} </th>
                                <th class="text-center">@{{proyecto.totalDolares.facturado | formatoMoneda}}</th>
                                <th class="text-center">@{{proyecto.totalDolares.porFacturar | formatoMoneda}}</th>
                                <th class="text-center">@{{proyecto.totalDolares.pagado | formatoMoneda}}</th>
                                <th class="text-center">@{{proyecto.totalDolares.pendiente | formatoMoneda}}</th>
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
      clienteSelect:null,
      totalmmonto:'',
      totalmfacturado:'',
      totalmporfacturar:'',
      totalmpendiente:'',
      totalmpagado:'',
      totaldmonto:'',
      totaldfacturado:'',
      totaldporfacturar:'',
      totaldpendiente:'',
      totaldpagado:''
    },
    mounted(){
       var vue =this;
       this.clienteSelect= $('#selectCliente').select2({ width: '100%'}).on('select2:select',function () {       
          var value = $("#selectCliente").select2('data');
          vue.clienteCargado = value[0].id
          vue.cargar();
          //this.tabla.columns(4).search(this.valor_proyectos).draw();
        });
       this.tabla = $("#tablaActividades").DataTable({
          "order":[],
        });

    },
    watch:{
        data: function(val){
            this.data.proyectos.forEach(element => {
                var totalDolares = {monto:0.0, facturado:0.0, porFacturar:0.0, pagado:0.0, pendiente:0.0};
                var totalMxn     = {monto:0.0, facturado:0.0, porFacturar:0.0, pagado:0.0, pendiente:0.0};
                element.forEach(element2 => {
                   if(element2.moneda=="Dolares"){
                    totalDolares.monto+=element2.total;
                    totalDolares.facturado+=element2.facturado;
                    totalDolares.pagado+=element2.pagado;
                    totalDolares.pendiente+=element2.pendiente;
                   }else{
                    totalMxn.monto+=element2.total;
                    totalMxn.facturado+=element2.facturado;
                    totalMxn.pagado+=element2.pagado;
                    totalMxn.pendiente+=element2.pendiente;
                   } 
                });
                totalDolares.porFacturar=+totalDolares.monto-totalDolares.facturado;
                totalMxn.porFacturar=+totalMxn.monto-totalMxn.facturado;
                element['totalDolares']={...totalDolares};
                element['totalMxn']=totalMxn;

            });

            this.totalmmonto = this.totalMxn.monto;
            this.totalmfacturado = this.totalMxn.facturado;
            this.totalmporfacturar = this.totalMxn.porFacturar;
            this.totalmpagado = this.totalMxn.pagado;
            this.totalmpendiente = this.totalMxn.pendiente;

            this.totaldmonto = this.totalDolares.monto;
            this.totaldfacturado = this.totalDolares.facturado;
            this.totaldporfacturar = this.totalDolares.porFacturar;
            this.totaldpagado = this.totalDolares.pagado;
            this.totaldpendiente = this.totalDolares.pendiente;
        }
     
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
      },
      pdf(){
        datos = this.tabla.rows( { search:'applied' } ).data(); 
        var datosfinal = {
          datos : [],       
          totalMxnMonto: this.totalmmonto,
          totalMxnFacturado: this.totalmfacturado,
          totalMxnPorfacturar: this.totalmporfacturar,
          totalMxnPagado: this.totalmpagado,
          totalMxnPendiente: this.totalmpendiente,
          totalUsdMonto: this.totaldmonto,
          totalUsdFacturado: this.totaldfacturado,
          totalUsdPorFacturar: this.totaldporfacturar,
          totalUsdPagado: this.totaldpagado,
          totalUsdPendiente: this.totaldpendiente,
        };
        var dat = [];

        for (var i = datos.length - 1; i >= 0; i--) {
          var data = {}
          Object.assign(data, datos[i]);
          //console.log(data);
          datosfinal.datos.push(data);
        }

        //console.log(datosfinal);

        var formData = objectToFormData(datosfinal, {indices: true});

        //console.log(datos);

        axios.post('/reportes/cuentaCliente/pdf', formData,{headers: {'Content-Type': 'multipart/form-data'}
        })
        .then(({data}) => {
          swal({
            title: "Reporte generado",
            text: "",
            type: "success"
          }).then(()=>{
            window.open('/storage/reportes/cuenta.pdf', '_blank').focus();
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

      }
    }})
</script>
@stop