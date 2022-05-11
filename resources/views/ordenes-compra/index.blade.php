@extends('layouts/default')

{{-- Page title --}}
@section('title')
  Ordenes Compra | @parent
@stop

@section('header_styles')

<!-- <style></style> -->
@stop

{{-- Page content --}}
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1 style="font-weight: bolder;">Ordenes De Compra Proyecto {{$ordenes->first()->proyecto_nombre}} // {{$cotizacion->numero}}</h1>
</section>
<!-- Main content -->
<section class="content" id="content">
  <div class="row">
    <div class="col-lg-12">
      <div class="panel">
        <div class="panel-heading">
          <h3 class="panel-title text-right">
            <span class="p-10 pull-left">Lista de Ordenes</span>
            <a href="{{route('proyectos-aprobados.ordenes-compra.create', $ordenes->first()->proyecto_id)}}"
              class="btn btn-primary" style="color: #fff;">
              <i class="fas fa-plus"></i> Nueva Orden
            </a>
          </h3>
        </div>
        <div class="panel-body">
          <div class="table-responsive">
            <table id="tabla" class="table table-bordred">
              <thead>
                <tr style="background-color:#907ff3">
                  <th>#</th>
                  <th>Numero</th>
                  <th>Proveedor</th>
                  <th>Producto</th>
                  <th>Cantidad</th>
                  <th>Estatus</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(orden,index) in ordenes">
                  <td>@{{index+1}}</td>
                  <td>@{{orden.numero}}</td>
                  <td>@{{orden.proveedor_empresa}}</td>
                  <td>
                    <span v-for="(entrada, index) in orden.entradas">
                      @{{index+1}}.- @{{entrada.producto.nombre}} <br />
                    </span>
                  </td>
                  <td>
                    <span v-for="(entrada, index) in orden.entradas">
                      @{{index+1}}.-
                        <span v-if="entrada.conversion">@{{entrada.cantidad_convertida}} @{{entrada.conversion}}</span>
                        <span v-else>@{{entrada.cantidad}} @{{entrada.medida}}</span>
                      <br />
                    </span>
                  </td>
                  <td>@{{orden.status}}</td>
                  <td class="text-right">
                    <template v-if="orden.status!='Pendiente' && orden.status!='Cancelada'">
                      <a class="btn btn-xs btn-info" title="Ver"
                        :href="'/proyectos-aprobados/'+orden.proyecto_id+'/ordenes-compra/'+orden.id">
                        <i class="far fa-eye"></i>
                      </a>
                      <a v-if="orden.archivo" class="btn btn-xs btn-warning" title="PDF" :href="orden.archivo"
                        :download="'INTERCORP-PO '+orden.numero+' '+orden.cliente_nombre+' '+orden.proyecto_nombre+'.pdf'">
                        <i class="far fa-file-pdf"></i>
                      </a>
                    </template>
                    <a v-if="orden.status=='Pendiente' || orden.status=='Rechazada'"
                      class="btn btn-xs btn-success" title="Editar"
                      :href="'/proyectos-aprobados/'+orden.proyecto_id+'/ordenes-compra/'+orden.id+'/editar'">
                      <i class="fas fa-pencil-alt"></i>
                    </a>
                    <a v-if="orden.status=='Pendiente'"
                      class="btn btn-xs btn-warning" title="Comprar"
                      :href="'/proyectos-aprobados/'+orden.proyecto_id+'/ordenes-compra/'+orden.id">
                      <i class="fas fa-cash-register"></i>
                    </a>
                    
                    @role('Administrador')
                    <button v-if="orden.status=='Por Autorizar'" class="btn btn-xs btn-primary"
                      title="Aprobar" @click="ordenModal=orden; openAprobar=true;">
                      <i class="far fa-thumbs-up"></i>
                    </button>

                    <button v-if="orden.status=='Aprobada'" class="btn btn-xs btn-danger"
                      title="Des Aprobar" @click="ordenModal=orden; openDesAprobar=true;">
                      <i class="fa fa-arrow-down"></i>
                    </button>
                    
                    <button v-if="orden.status=='Aprobada'" class="btn btn-xs btn-purple"
                      title="Confirmar" @click="ordenModal=orden; openConfirmar=true; ordenModal.monto_total_flete=orden.flete;ordenModal.monto_total_producto=orden.subtotal;ordenModal.tax=orden.iva;sumartot()">
                      <i class="fas fa-clipboard-check"></i>
                    </button>
                    @endrole
                    <button v-if="orden.status=='Por Autorizar'" class="btn btn-xs btn-danger"
                      title="Rechazar" @click="ordenModal=orden; openRechazar=true;">
                      <i class="far fa-thumbs-down"></i>
                    </button>
                    <button v-if="orden.status!='Aprobada' && orden.status!='Confirmada' && orden.status!='Cancelada'" 
                      class="btn btn-xs btn-danger" title="Cancelar" @click="cancelarOrden(orden)">
                      <i class="fas fa-times"></i>
                    </button>
                    <a v-if="orden.status=='Confirmada'" class="btn btn-xs text-primary" title="Confirmación Fabrica" 
                      :href="orden.confirmacion_fabrica"
                      target="_blank">
                      <i class="fas fa-clipboard-check"></i>
                    </a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <div class="row">
            <div class="col-md-12 text-right">
              <a href="{{ route('proyectos-aprobados.index') }}" class="btn btn-default">
                Regresar
              </a>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>

  <!-- Aprobar Modal -->
  <modal v-model="openAprobar" :title="'Aprobar orden '+ordenModal.numero" :footer="false">
    <form class="" @submit.prevent="aprobarOrden()">
      <h4>Archivos para autorización</h4>
      <div class="form-group">
        <ul style="list-style-type:disc;">
          <li v-for="(archivo, index) in ordenModal.archivos_autorizacion" style="margin-bottom:3px;">
            <a :href="archivo.liga" target="_blank">@{{archivo.nombre}}</a>
          </li>
        </ul>
      </div>
      <div class="form-group text-right">
        <button type="submit" class="btn btn-primary" :disabled="cargando">Aprobar</button>
        <button type="button" class="btn btn-default"
          @click="ordenModal={}; openAprobar=false;">
          Cancelar
        </button>
      </div>
    </form>
  </modal>
  <!-- /.Aprobar Modal -->

  <!-- DesAprobar Modal -->
  <modal v-model="openDesAprobar" :title="'Des Aprobar orden '+ordenModal.numero" :footer="false">
    <form class="" @submit.prevent="desaprobarOrden()">
      <h4>Desaprobar</h4>
      <div class="form-group text-right">
        <button type="submit" class="btn btn-primary" :disabled="cargando">Desaprobar</button>
        <button type="button" class="btn btn-default"
          @click="ordenModal={}; openDesAprobar=false;">
          Cancelar
        </button>
      </div>
    </form>
  </modal>
  <!-- /.DesAprobar Modal -->

  <!-- Rechazar Modal -->
  <modal v-model="openRechazar" :title="'Rechazar orden '+ordenModal.numero" :footer="false">
    <form class="" @submit.prevent="rechazarOrden()">
      <div class="form-group">
        <label class="control-label">Motivo de rechazo</label>
        <textarea name="motivo" class="form-control" v-model="ordenModal.motivo_rechazo" rows="5" cols="80" required/>
        </textarea>
      </div>
      <div class="form-group text-right">
        <button type="submit" class="btn btn-primary" :disabled="cargando">Rechazar</button>
        <button type="button" class="btn btn-default"
          @click="ordenModal={}; openRechazar=false;">
          Cancelar
        </button>
      </div>
    </form>
  </modal>
  <!-- /.Rechazar Modal -->

  <!-- Confirmar Modal -->
  <modal v-model="openConfirmar" :title="'Confirmar orden '+ordenModal.numero" :footer="false">
    <form class="" @submit.prevent="confirmarOrden()">
      <div class="form-group">
        <label class="control-label">Confirmación Fabrica</label>
        <div class="file-loading">
          <input id="confirmacion" name="confirmacion" type="file" ref="confirmacion"
            @change="fijarConfirmacion()" required />
        </div>
        <div id="confirmacion-file-errors"></div>
        <div class="col-md-4">
          <label class="control-label">Monto total del Producto</label>
          <input type="number" step=0.01 class="form-control" v-model="ordenModal.monto_total_producto" min="0.0" @change="sumartotal('monto_producto')"
             />
        </div>
        <div class="col-md-4">
          <label class="control-label">Monto total del Flete</label>
          <input type="number" step=0.01 class="form-control" v-model="ordenModal.monto_total_flete" min="0.0" @change="sumartotal('monto_flete')"
             />
        </div>
        <div class="col-md-4">
          <label class="control-label">Posibles Aumentos</label>
          <input type="number" step=0.01 class="form-control" v-model="ordenModal.posibles_aumentos" min="0.0" @change="sumartotal('posibles')"
             />
        </div>
        <div class="col-md-4">
          <label class="control-label">Tax</label>
          <input type="number" step=0.01 class="form-control" v-model="ordenModal.tax" min="0.0" @change="sumartotal('tax')"
             />
        </div>
        <div class="col-md-4">
          <label class="control-label">Monto total a Pagar</label>
          <input type="number" step=0.01 class="form-control" v-model="ordenModal.monto_total_pagar" min="0.0" readonly 
             />
        </div>
      </div>
      <div class="form-group text-right">
        <button type="submit" class="btn btn-primary" :disabled="cargando">Aceptar</button>
        <button type="button" class="btn btn-default"
          @click="ordenModal={}; openConfirmar=false;">
          Cancelar
        </button>
      </div>
    </form>
  </modal>
  <!-- /.Confirmar Modal -->

</section>

<!-- /.content -->
@stop

{{-- footer_scripts --}}
@section('footer_scripts')
<script>
const app = new Vue({
    el: '#content',
    data: {
      ordenes: {!! json_encode($ordenes) !!},
      ordenModal : {},
      openAprobar: false,
      openDesAprobar: false,
      openRechazar: false,
      openConfirmar: false,
      cargando: false
    },
    mounted(){
      this.tabla = $("#tabla").DataTable({"order": [[ 1, "asc" ]]});
      $("#confirmacion").fileinput({
        language: 'es',
        showPreview: false,
        showUpload: false,
        showRemove: false,
        allowedFileExtensions: ["jpg", "jpeg", "png", "pdf"],
        elErrorContainer: '#confirmacion-file-errors',
      });
    },
    methods: {
      fijarConfirmacion(){
        this.ordenModal.confirmacion_fabrica = this.$refs['confirmacion'].files[0];
      },
      sumartot(){
        this.ordenModal.monto_total_pagar = parseFloat(this.ordenModal.monto_total_flete) + parseFloat(this.ordenModal.monto_total_producto);
      },
      rechazarOrden(){
        this.cargando = true;
        axios.post(
        '/proyectos-aprobados/'+this.ordenModal.proyecto_id+'/ordenes-compra/'+this.ordenModal.id+'/rechazar',
        {'motivo_rechazo': this.ordenModal.motivo_rechazo}).then(({data}) => {
          this.ordenModal.status = 'Rechazada';
          this.openRechazar = false;
          this.cargando = true;
          swal({
            title: "Orden Rechazada",
            text: "La orden ha sido rechazada",
            type: "success"
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
      },//fin rechazar
      aprobarOrden(){
        this.cargando = true;
        axios.get('/proyectos-aprobados/'+this.ordenModal.proyecto_id+'/ordenes-compra/'+this.ordenModal.id+'/aprobar', {})
        .then(({data}) => {
          this.ordenModal.status = 'Aprobada';
          this.openAprobar = false;
          this.cargando = false;
          swal({
            title: "Exito",
            text: "La orden ha sido aprobada",
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
      },//aceptar
      desaprobarOrden(){
        this.cargando = true;
        axios.get('/proyectos-aprobados/'+this.ordenModal.proyecto_id+'/ordenes-compra/'+this.ordenModal.id+'/desaprobar', {})
        .then(({data}) => {
          this.ordenModal.status = 'Por Autorizar';
          this.openDesAprobar = false;
          this.cargando = false;
          swal({
            title: "Exito",
            text: "La orden ha sido desaprobada",
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
      },//aceptar
      cancelarOrden(orden){
        swal({
          title: 'Cuidado',
          text: "Cancelar la orden "+orden.id+"?",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Si, Cancelar',
          cancelButtonText: 'No, dejar sin cancelar',
        }).then((result) => {
          if (result.value) {
            axios.delete('/proyectos-aprobados/'+orden.proyecto_id+'/ordenes-compra/'+orden.id, {})
            .then(({data}) => {
              orden.status = 'Cancelada';
              swal({
                title: "Exito",
                text: "La orden se ha cancelado",
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
      },//cancelar
      sumartotal(valor){
       
       this.ordenModal.monto_total_pagar = parseFloat(this.ordenModal.monto_total_flete) + parseFloat(this.ordenModal.monto_total_producto) + parseFloat(this.ordenModal.tax) + parseFloat(this.ordenModal.posibles_aumentos);

       /*
        if (valor == 'monto_producto') {
          if (this.ordenModal.monto_total_producto != null || this.ordenModal.monto_total_producto != "" ) {
              this.ordenModal.monto_total_pagar = parseFloat(this.ordenModal.monto_total_pagar) + parseFloat(this.ordenModal.monto_total_producto); 
          }  
        }
        if (valor == 'monto_flete') {
            if (this.ordenModal.monto_total_flete != null || this.ordenModal.monto_total_flete != "" ) {
                this.ordenModal.monto_total_pagar =parseFloat(this.ordenModal.monto_total_pagar) +  parseFloat(this.ordenModal.monto_total_flete);  
            }
        }

        if (valor == 'tax') {
            if (this.ordenModal.tax != null || this.ordenModal.tax != "" ) {
              this.ordenModal.monto_total_pagar = parseFloat(this.ordenModal.monto_total_pagar) +  parseFloat(this.ordenModal.tax);  
          }
        }

        if (valor == 'posibles') {
            if (this.ordenModal.posibles_aumentos != null || this.ordenModal.posibles_aumentos != "" ) {
              this.ordenModal.monto_total_pagar = parseFloat(this.ordenModal.monto_total_pagar) +  parseFloat(this.ordenModal.posibles_aumentos);  
          }

        }
        */
           
        
      },
      confirmarOrden(){


        var data = {};
        data.confirmacion_fabrica = this.ordenModal.confirmacion_fabrica;
        data.monto_total_producto = this.ordenModal.monto_total_producto;
        data.monto_total_pagar = this.ordenModal.monto_total_pagar;
        data.monto_total_flete = this.ordenModal.monto_total_flete;
        data.tax = this.ordenModal.tax;
        data.posibles_aumentos = this.ordenModal.posibles_aumentos;
        console.log(data);


        var formData = objectToFormData(data, {indices:true});

        this.cargando = true;
        axios.post('/proyectos-aprobados/'+this.ordenModal.proyecto_id+'/ordenes-compra/'+this.ordenModal.id+'/confirmar', 
        formData, { headers: { 'Content-Type': 'multipart/form-data'}
      })
        .then(({data}) => {
          this.ordenModal.status = 'Confirmada';
          this.ordenModal.confirmacion_fabrica = data.confirmacion;
          
          $("#confirmacion").fileinput('clear');

          this.ordenModal.monto_total_producto = 0.0;
          this.ordenModal.monto_total_pagar= 0.0;
          this.ordenModal.monto_total_flete= 0.0;
          this.ordenModal.tax= 0.0;
          this.ordenModal.posibles_aumentos= 0.0;

          this.openConfirmar = false;
          this.cargando = false;

          swal({
            title: "Cotizacion Aceptada",
            text: "La orden ha sido confirmada y se ha generado una orden en proceso, ¿desea ir a la orden en proceso?",
            icon: "warning",
            showCancelButton: true,
            buttons: [
              'No!',
              'Si, ir a la orden en proceso!'
            ],
            dangerMode: true,
          }).then(function(isConfirm) {
            if (isConfirm.value) {
              window.location.href = "/proyectos-aprobados/";    
            } else {
              swal("Permanecer");
            }
          })

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


      },//fin confirmarOrden
    }
});
</script>
@stop
