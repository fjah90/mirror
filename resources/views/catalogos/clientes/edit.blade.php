@extends('layouts/default')

{{-- Page title --}}
@section('title')
    Editar Cliente | @parent
@stop

@section('header_styles')
@stop

{{-- Page content --}}
@section('content')
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>Clientes</h1>
  </section>
  <!-- Main content -->
  <section class="content" id="content">
    <div class="row">
      <div class="col-lg-12">
        <div class="panel ">
          <div class="panel-heading">
            <h3 class="panel-title">Editar Cliente</h3>
          </div>
          <div class="panel-body">
            <form class="" @submit.prevent="guardar()">
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Usuario</label>
                    <select class="form-control" name="usuario_id" v-model='cliente.usuario_id' required>
                      @foreach($usuarios as $id => $nombre)
                        <option value="{{$id}}">{{$nombre}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Tipo</label>
                    <select class="form-control" name="tipo_id" v-model='cliente.tipo_id' required>
                      @foreach($tipos as $tipo)
                        <option value="{{$tipo->id}}">{{$tipo->nombre}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Nombre</label>
                    <input type="text" class="form-control" name="nombre" v-model="cliente.nombre" required />
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">RFC</label>
                    <input type="text" class="form-control" name="rfc" v-model="cliente.rfc" />
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="control-label">Razon Social</label>
                    <input type="text" class="form-control" name="razon_social" v-model="cliente.razon_social" />
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Telefono</label>
                    <input type="tel" class="form-control" v-mask="['(###) ###-####','+#(###)###-####']"
                     v-model="cliente.telefono"
                    />
                  </div>
                </div>
                <div class="col-md-8">
                  <div class="form-group">
                    <label class="control-label">Email</label>
                    <input type="text" class="form-control" name="email" v-model="cliente.email" />
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Calle</label>
                    <input type="text" class="form-control" name="calle" v-model="cliente.calle" />
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Numero</label>
                    <input type="text" class="form-control" name="numero" v-model="cliente.numero" />
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Numero Interior</label>
                    <input type="text" class="form-control" name="ninterior" v-model="cliente.ninterior" />
                  </div>
                </div>
              </div>
              @if($cliente->nacional)
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Colonia</label>
                    <input type="text" class="form-control" name="colonia" v-model="cliente.colonia" />
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Delegacion</label>
                    <input type="text" class="form-control" name="delagacion" v-model="cliente.delegacion" />
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">C. Postal</label>
                    <input type="text" class="form-control" name="cp" v-model="cliente.cp" />
                  </div>
                </div>
              </div>
              @endif
              <div class="row">
                @if(!$cliente->nacional)
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">C. Postal</label>
                    <input type="text" class="form-control" name="cp" v-model="cliente.cp" />
                  </div>
                </div>
                @endif
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Ciudad</label>
                    <input type="text" class="form-control" name="ciudad" v-model="cliente.ciudad" />
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Estado</label>
                    <input type="text" class="form-control" name="estado" v-model="cliente.estado" />
                  </div>
                </div>
              </div>
              @if(!$cliente->nacional)
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Pais</label>
                    <input type="text" class="form-control" name="pais" v-model="cliente.pais" />
                  </div>
                </div>
                <div class="col-md-8">
                  <div class="form-group">
                    <label class="control-label">Datos Adicionales</label>
                    <input type="text" class="form-control" name="email" v-model="cliente.adicionales" />
                  </div>
                </div>
              </div>
              @else
              <div class="row">
                <div class="col-md-8">
                  <div class="form-group">
                    <label class="control-label">Datos Adicionales</label>
                    <input type="text" class="form-control" name="email" v-model="cliente.adicionales" />
                  </div>
                </div>
              </div>
              @endif
              <div class="row">
                <div class="col-md-12 text-right">
                  <button type="submit" class="btn btn-success" :disabled="cargando">
                    <i class="fas fa-save"></i>
                    Actualizar Cliente
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
          <div class="panel-heading">
            <h3 class="panel-title">Contactos del Cliente</h3>
          </div>
          <div class="panel-body">
            <div class="row">
              <div class="col-md-12">
                <div class="table-responsive">
                  <table class="table">
                    <thead>
                      <tr>
                        <th>Nombre</th>
                        <th>Cargo</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Teléfono 2</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(contacto, index) in cliente.contactos">
                        <td>@{{contacto.nombre}}</td>
                        <td>@{{contacto.cargo}}</td>
                        <td>@{{contacto.email}}</td>
                        <td>@{{contacto.tipo_telefono}} @{{contacto.telefono}} Ext. @{{contacto.extencion_telefono}}</td>
                        <td>@{{contacto.tipo_telefono2}} @{{contacto.telefono2}} Ext. @{{contacto.extencion_telefono2}}</td>
                        <td class="text-right">
                          <button class="btn btn-success" data-toggle="tooltip" title="Editar"
                            @click="editarContacto(contacto, index)">
                            <i class="fas fa-edit"></i>
                          </button>
                          <button class="btn btn-danger" data-toggle="tooltip" title="Borrar"
                            @click="borrarContacto(index)">
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
            <form class="" @submit.prevent="agregarContacto()">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label">Nombre</label>
                    <input type="text" class="form-control" name="nombre" v-model="contacto.nombre" required />
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label">Cargo</label>
                    <input type="text" class="form-control" name="cargo" v-model="contacto.cargo" required />
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label class="control-label">Email</label>
                    <input type="text" class="form-control" name="email" v-model="contacto.email" />
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <h4>Teléfono</h4>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Numero</label>
                    <input type="tel" class="form-control" v-mask="['(###) ###-####','+#(###)###-####']"
                     v-model="contacto.telefono"
                    />
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Extención</label>
                    <input type="text" class="form-control" v-model="contacto.extencion_telefono" />
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Tipo</label>
                    <select class="form-control" v-model="contacto.tipo_telefono">
                      <option value="Oficina">Oficina</option>
                      <option value="Celular">Celular</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <h4>Teléfono 2</h4>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Numero</label>
                    <input type="tel" class="form-control" v-mask="['(###) ###-####','+#(###)###-####']"
                     v-model="contacto.telefono2"
                    />
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Extención</label>
                    <input type="text" class="form-control" v-model="contacto.extencion_telefono2" />
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label class="control-label">Tipo</label>
                    <select class="form-control" v-model="contacto.tipo_telefono2">
                      <option value="Oficina">Oficina</option>
                      <option value="Celular">Celular</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12 text-right">
                  <button style="margin-top:25px;" type="submit" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Contacto
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
      translations: translationsES,
      cliente: {
        tipo_id: '{{$cliente->tipo_id}}',
        usuario_id: '{{$cliente->usuario_id}}',
        nombre: '{{$cliente->nombre}}',
        rfc: '{{$cliente->rfc}}',
        razon_social: '{{$cliente->razon_social}}',
        telefono: '{{$cliente->telefono}}',
        email: '{{$cliente->email}}',
        calle: '{{$cliente->calle}}',
        numero: '{{$cliente->numero}}',
        ninterior: '{{$cliente->ninterior}}',
        colonia: '{{$cliente->colonia}}',
        delegacion: '{{$cliente->delegacion}}',
        cp: '{{$cliente->cp}}',
        ciudad: '{{$cliente->ciudad}}',
        estado: '{{$cliente->estado}}',
        pais: '{{$cliente->pais}}',
        nacional: '{{$cliente->nacional}}',
        adicionales: '{{$cliente->adicionales}}',
        contactos: {!! json_encode($cliente->contactos) !!}
      },
      contacto: {
        nombre: '',
        cargo: '',
        telefono: '',
        telefono2: '',
        extencion_telefono: '',
        extencion_telefono2: '',
        tipo_telefono: '',
        tipo_telefono2: '',
        email: '',
      },
      cargando: false,
    },
    computed:{
      mascara_telefono_cliente(){
        if(this.cliente.telefono.charAt(0)=='+')
          return '+# (###) ###-####';
        else return '(###) ###-####';
      }
    },
    methods: {
      agregarContacto(){
        if(this.contacto.nombre.trim()=="" || this.contacto.cargo.trim()==""){
          swal({
            title: "Atención",
            text: "El nombre y cargo del contacto son obligatorios",
            type: "warning"
          });
          return false;
        }

        this.cliente.contactos.push(this.contacto);
        this.contacto = {
          nombre: '',
          cargo: '',
          telefono: '',
          telefono2: '',
          extencion_telefono: '',
          extencion_telefono2: '',
          tipo_telefono: '',
          tipo_telefono2: '',
          email: '',
        };
      },
      editarContacto(contacto, index){
        this.contacto = contacto;
        this.cliente.contactos.splice(index, 1);
      },
      borrarContacto(index){
        this.cliente.contactos.splice(index, 1);
      },
      guardar(){
        this.cargando = true;
        axios.put('/clientes/{{$cliente->id}}', this.cliente)
        .then(({data}) => {
          this.cargando = false;
          swal({
            title: "Cliente Actualizado",
            text: "",
            type: "success"
          }).then(()=>{
            window.location = "/clientes";
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
