@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Nuevo Proveedor | @parent
@stop

@section('header_styles')
@stop

{{-- Page content --}}
@section('content')
  <!-- Content Header (Page header) -->
  <section class="content-header" style="background-color:#12160F; color:#B68911;">
    <h1>Nuevo Proveedor {{ ($nacional)?'Nacional':'Internacional' }}</h1>
  </section>
  <!-- Main content -->
  <section class="content" id="content">
    <form class="" @submit.prevent="guardar()">
      <div class="row">
        <div class="col-lg-12">
          <div class="panel ">
            <div class="panel-heading" style="background-color:#12160F; color:#B68911;">
              <h3 class="panel-title">Datos Generales</h3>
            </div>
            <div class="panel-body">
              <div class="row form-group">
                <div class="col-md-6">
                  <label class="control-label">Tipo</label>
                  <select name="tipo" v-model="proveedor.tipo_id" class="form-control">
                    @foreach($tipos as $tipo)
                    <option value="{{$tipo->id}}">{{$tipo->nombre}}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="row form-group">
                <div class="col-md-8">
                  <label class="control-label">Nombre Empresa</label>
                  <input type="text" class="form-control" name="empresa" v-model="proveedor.empresa" required />
                </div>
                <div class="col-md-4">
                  <label class="control-label">Número Proveedor</label>
                  <input type="text" class="form-control" name="  numero_proveedor" v-model="proveedor. numero_proveedor" />
                </div>
              </div>
              <div class="row form-group">
                <div class="col-md-8">
                  <label class="control-label">Razon Social</label>
                  <input type="text" class="form-control" name="razon_social" v-model="proveedor.razon_social" />
                </div>
                <div class="col-md-4">
                  <label class="control-label">{{ ($nacional)?'RFC':'TAX ID NO' }}:</label>
                  <input type="text" class="form-control" name="identidad_fiscal" v-model="proveedor.identidad_fiscal" />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-12">
          <div class="panel ">
            <div class="panel-heading" style="background-color:#12160F; color:#B68911;">
              <h3 class="panel-title">Dirección</h3>
            </div>
            <div class="panel-body">
              <div class="row form-group">
                <div class="col-md-4">
                  <label class="control-label">Calle</label>
                  <input type="text" class="form-control" name="calle" v-model="proveedor.calle" />
                </div>
                <div class="col-md-4">
                  <label class="control-label">Numero</label>
                  <input type="text" class="form-control" name="numero" v-model="proveedor.numero" />
                </div>
              </div>
              @if($nacional)
              <div class="row form-group">
                <div class="col-md-4">
                  <label class="control-label">Colonia</label>
                  <input type="text" class="form-control" name="colonia" v-model="proveedor.colonia" />
                </div>
                <div class="col-md-4">
                  <label class="control-label">Delegación</label>
                  <input type="text" class="form-control" name="delegacion" v-model="proveedor.delegacion" />
                </div>
                <div class="col-md-4">
                  <label class="control-label">C. Postal</label>
                  <input type="text" class="form-control cp" name="cp" v-model="proveedor.cp" />
                </div>
              </div>
              <div class="row form-group">
                <div class="col-md-4">
                  <label class="control-label">Ciudad</label>
                  <input type="text" class="form-control municipio" name="ciudad" v-model="proveedor.ciudad" />
                </div>
                <div class="col-md-4">
                  <label class="control-label">Estado</label>
                  <input type="text" class="form-control estado" name="estado" v-model="proveedor.estado" />
                </div>
                <!-- <div class="col-md-4">
                  <label class="control-label">Pais</label>
                  <input type="text" class="form-control" name="pais" v-model="proveedor.pais" />
                </div> -->
              </div>
              @else
              <div class="row form-group">
                <div class="col-md-4">
                  <label class="control-label">Ciudad</label>
                  <input type="text" class="form-control" name="ciudad" v-model="proveedor.ciudad" />
                </div>
                <div class="col-md-4">
                  <label class="control-label">Estado</label>
                  <input type="text" class="form-control" name="estado" v-model="proveedor.estado" />
                </div>
                <div class="col-md-4">
                  <label class="control-label">ZIP Code</label>
                  <input type="text" class="form-control" name="colonia" v-model="proveedor.cp" />
                </div>
              </div>
              <div class="row form-group">
                <div class="col-md-4">
                  <label class="control-label">Pais</label>
                  <input type="text" class="form-control" name="pais" v-model="proveedor.pais" />
                </div>
              </div>
              @endif
            </div>
          </div>
        </div>
      </div>

      <div class="panel">
        <div class="panel-heading" style="background-color:#12160F; color:#B68911;">
          <h3 class="panel-title">Otros</h3>
        </div>
        <div class="panel-body">
          <div class="row form-group">
            <div class="col-md-12">
              <label class="control-label">Pagina Web</label>
              <input type="text" class="form-control" name="pagina_web" v-model="proveedor.pagina_web" />
            </div>
          </div>
          <div class="row form-group">
            <div class="col-md-12">
              <label class="control-label">Datos Adicionales</label>
              <input type="text" class="form-control" name="adicionales" v-model="proveedor.adicionales" />
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-12">
          <div class="panel ">
            <div class="panel-heading" style="background-color:#12160F; color:#B68911;">
              <h3 class="panel-title">Datos Bancarios</h3>
            </div>
            <div class="panel-body">
               <div class="row">
                <div class="col-md-12">
                  <label class="control-label">Descripción de la empresa</label>
                  <textarea  name="decripcion_empresa" class="form-control" rows="4"  cols="80" v-model="proveedor.decripcion_empresa" required></textarea>
                </div>
              </div><br>
              <div class="row form-group">
                <div class="col-md-4">
                  <label class="control-label">Moneda</label>
                  <select class="form-control" name="moneda" v-model="proveedor.moneda">
                    <option value="Pesos">Pesos</option>
                    <option value="Dolares">Dolares</option>
                  </select>
                </div>
                <div class="col-md-4">
                  <label class="control-label">Limite Credito</label>
                  <input type="number" min="0" step="0.01" class="form-control" name="limite_credito" v-model="proveedor.limite_credito" />
                </div>
                <div class="col-md-4">
                  <label class="control-label">Dias Credito</label>
                  <input type="number" min="0" step="1" class="form-control" name="dias_credito" v-model="proveedor.dias_credito" />
                </div>
              </div>
              <div class="row form-group">
                <div class="col-md-4">
                  <label class="control-label">Banco</label>
                  <input type="text" class="form-control" name="banco" v-model="proveedor.banco" />
                </div>
                <div class="col-md-4">
                  <label class="control-label">Número de Cuenta</label>
                  <input type="text" class="form-control" name="numero_cuenta" v-model="proveedor.numero_cuenta" />
                </div>
                {{-- <div class="col-md-4">
                  <label class="control-label">No Cuenta (Intercorp/Cliente)</label>
                  <input type="text" class="form-control" name="cuenta_interna" v-model="proveedor.cuenta_interna" />
                </div> --}}
                @if($nacional)
                <div class="col-md-4">
                  <label class="control-label">Clave Interbancaria</label>
                  <input type="text" class="form-control" name="clave_interbancaria" v-model="proveedor.clave_interbancaria" />
                </div>
                @else
                <div class="col-md-2">
                  <div class="form-group">
                    <label class="control-label">SWIF</label>
                    <input type="text" class="form-control" name="swift" v-model="proveedor.swift" />
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <label class="control-label">ABA</label>
                    <input type="text" class="form-control" name="aba" v-model="proveedor.aba" />
                  </div>
                </div>
                @endif
              </div>
              @if($nacional)
              <div class="row form-group">
                <div class="col-md-4">
                  <label class="control-label">Colonia</label>
                  <input type="text" class="form-control" name="banco_colonia" v-model="proveedor.banco_colonia" />
                </div>
                <div class="col-md-4">
                  <label class="control-label">Delegación</label>
                  <input type="text" class="form-control" name="banco_delegacion" v-model="proveedor.banco_delegacion" />
                </div>
                <div class="col-md-4">
                  <label class="control-label">C. Postal</label>
                  <input type="text" class="form-control cp1" name="banco_cp" v-model="proveedor.banco_cp" />
                </div>
              </div>
              <div class="row form-group">
                <div class="col-md-4">
                  <label class="control-label">Ciudad</label>
                  <input type="text" class="form-control municipio1" name="banco_ciudad" v-model="proveedor.banco_ciudad" />
                </div>
                <div class="col-md-4">
                  <label class="control-label">Estado</label>
                  <input type="text" class="form-control estado1" name="banco_estado" v-model="proveedor.banco_estado" />
                </div>
                <div class="col-md-4">
                  <label class="control-label">Pais</label>
                  <input type="text" class="form-control" name="banco_pais" v-model="proveedor.banco_pais" />
                </div>
              </div>
              @else
              <div class="row form-group">
                <div class="col-md-4">
                  <label class="control-label">Ciudad</label>
                  <input type="text" class="form-control" name="banco_ciudad" v-model="proveedor.banco_ciudad" />
                </div>
                <div class="col-md-4">
                  <label class="control-label">Estado</label>
                  <input type="text" class="form-control" name="banco_estado" v-model="proveedor.banco_estado" />
                </div>
                <div class="col-md-4">
                  <label class="control-label">ZIP Code</label>
                  <input type="text" class="form-control" name="banco_cp" v-model="proveedor.banco_cp" />
                </div>
              </div>
              <div class="row form-group">
                <div class="col-md-4">
                  <label class="control-label">Pais</label>
                  <input type="text" class="form-control" name="banco_pais" v-model="proveedor.banco_pais" />
                </div>
              </div>
              @endif
            </div>
          </div>
        </div>
      </div>
              
      <div class="row">
        <div class="col-md-12 text-center">
          <a class="btn btn-default" href="{{route('proveedores.index')}}" style="margin-right:20px; color:#000; background-color:#B3B3B3">
            Regresar
          </a>
          <button type="submit" class="btn btn-primary" :disabled="cargando" style="background-color:#12160F; color:#B68911;">
            <i class="fas fa-save"></i>
            Guardar Proveedor
          </button>
        </div>
      </div>
    </form>
  </section>
  <!-- /.content -->
@stop

{{-- footer_scripts --}}
@section('footer_scripts')

<script>
const app = new Vue({
    el: '#content',
    data: {
      translations: translationsES,
      proveedor: {
        tipo_id: '',
        nacional: {{ ($nacional)?"true":"false" }},
        empresa: '',
        numero_proveedor: '',
        razon_social: '',
        identificacion_fiscal: '{{ ($nacional)?"RFC":"TAX ID NO" }}',
        identidad_fiscal: '',
        // calle: '',
        // nexterior: '',
        colonia: '',
        delegacion: '',
        cp: '',
        ciudad: '',
        estado: '',
        pais: '',
        pagina_web: '',
        adicionales: '',
        decripcion_empresa: '',
        moneda: '',
        limite_credito: '',
        dias_credito: '',
        banco: '',
        numero_cuenta: '',
        clave_interbancaria: '',
        // cuenta_intercorp: '',
        swift: '',
        aba: '',
        banco_colonia: '',
        banco_delegacion: '',
        banco_ciudad: '',
        banco_estado: '',
        banco_zipcode: '',
        banco_pais: ''
      },
      cargando: false,
    },
    methods: {
      guardar(){
        this.cargando = true;
        axios.post('/proveedores', this.proveedor)
        .then(({data}) => {
          this.cargando = false;
          swal({
            title: "Proveedor Guardado",
            text: "",
            type: "success"
          }).then(()=>{
            window.location = "/proveedores/"+data.proveedor.id+"/editar?contactos";
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

$('.cp').on('keyup', function (e) {
  var cp = $(this).val();
  if(cp.length >= 5) {
    $.get('http://sepomex.789.mx/' + cp, function (data) {
      if(data.estados.length >= 1) {
        $('.estado').first().val(data.estados[0]).change();
      }
      if(data.municipios.length >= 1) {
        $('.municipio').first().val(data.municipios[0]).change();
      }
    });
  }
});

$('.cp1').on('keyup', function (e) {
  var cp1 = $(this).val();
  if(cp1.length >= 5) {
    $.get('http://sepomex.789.mx/' + cp1, function (data) {
      if(data.estados.length >= 1) {
        $('.estado1').first().val(data.estados[0]).change();
      }
      if(data.municipios.length >= 1) {
        $('.municipio1').first().val(data.municipios[0]).change();
      }
    });
  }
});
</script>
@stop
