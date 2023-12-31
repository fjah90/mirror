@extends($layout==="iframe"?'layouts/iframe' : 'layouts/default')


{{-- Page title --}}
@section('title')
    Nuevo Cliente | @parent
@stop

@section('header_styles')
@stop

{{-- Page content --}}
@section('content')
    <!-- Content Header (Page header) -->

    <section class="content-header" style="background-color:#12160F; color:#B68911;">
        <h1>Nuevo Cliente {{ ($nacional)?"Nacional":"Extranjero" }}</h1>
    </section>
    <!-- Main content -->
    <section class="content" id="content">
        <form @submit.prevent=guardar()>
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel">
                        <div class="panel-heading" style="background-color:#12160F; color:#B68911;">
                            <h3 class="panel-title">Datos Generales</h3>
                        </div>
                        <div class="panel-body">
                            <div class="row form-group">
                                <div class="col-md-6">
                                    <label class="control-label">Usuario<strong style="color: grey"> *</strong></label>
                                    <select class="form-control" name="usuario_id" v-model='cliente.usuario_id'
                                            required>
                                        @foreach($usuarios as $id => $nombre)
                                            <option value="{{$id}}">{{$nombre}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Usuario(s) adicional</label>
                                        <select2multags :options="user.userOptions" v-model="user.users" style="width:100%;">
                                        </select2multags>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="control-label">Tipo<strong style="color: grey"> *</strong></label>
                                    <select class="form-control" name="tipo_id" v-model='cliente.tipo_id' required>
                                        @foreach($tipos as $tipo)
                                            <option value="{{$tipo->id}}">{{$tipo->nombre}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="control-label">Diseñador<strong style="color: grey"> *</strong></label>
                                    <select class="form-control" name="vendedor_id" v-model='cliente.vendedor_id'
                                            required>
                                        @foreach($vendedores as $id => $nombre)
                                            <option value="{{$id}}">{{$nombre}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row form-group">
                                  <div class="col-md-6">
                                    <label class="control-label">Categoría de Cliente<strong style="color: grey"> *</strong></label>
                                    <select class="form-control" name="categoria_cliente_id"  required v-model=cliente.categoria_cliente_id required>
                                    @foreach($categorias as $categoria)
                                        <option value="{{$categoria->id}}">{{$categoria->nombre}}</option>
                                    @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="control-label">Preferencias y necesidades</label>
                                    <input type="text" class="form-control" name="preferencias" v-model="cliente.preferencias">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="control-label">Nombre<strong style="color: grey"> *</strong></label>
                                    <input type="text" class="form-control" name="nombre" v-model="cliente.nombre"
                                           required/>
                                </div>
                                 <div class="col-md-6">
                                    <label class="control-label">Razon Social</label>
                                    <input type="text" class="form-control" name="razon_social"
                                           v-model="cliente.razon_social"/>
                                </div>
                            </div>
                            <!--div class="row form-group">
                                {{--@if($nacional)
                                    <div class="col-md-4">
                                        <label class="control-label">RFC</label>
                                        <input type="text" class="form-control" name="rfc" v-model="cliente.rfc"/>
                                    </div>
                                @endif--}}
                            </div--><br>
                              <div class="row form-group">
                                    <div class="col-md-6">
                                        <label class="control-label">Telefono</label>
                                        <input type="text" class="form-control" name="telefono" v-model="cliente.telefono"/>
                                    </div>
                                <div class="col-md-6">
                                    <label class="control-label">Email</label>
                                    <input type="text" class="form-control" name="email" v-model="cliente.email"/>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel ">
                        <div class="panel-heading" style="background-color:#12160F; color:#B68911;">
                            <h3 class="panel-title">Dirección</h3>
                        </div>
                        <div class="panel-body">
                            <div class="row form-group">
                                <div class="{{($nacional) ? 'col-md-4' : 'col-md-12'}}">
                                    <label class="control-label">{{($nacional) ? 'Calle' : 'Dirección'}}</label>
                                    <input type="text" class="form-control" name="calle" v-model="cliente.calle" maxlength="191"/>
                                </div>
                                @if($nacional)
                                    <div class="col-md-4">
                                        <label class="control-label">Numero Exterior</label>
                                        <input type="text" class="form-control" name="nexterior"
                                               v-model="cliente.nexterior"/>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="control-label">Numero Interior</label>
                                        <input type="text" class="form-control" name="ninterior"
                                               v-model="cliente.ninterior"/>
                                    </div>
                                @endif
                                {{--</div>
                                <div class="row form-group">--}}
                                <div class="col-md-4">
                                    <label class="control-label">C. Postal</label>
                                    <input type="text" class="form-control cp" name="cp" v-model="cliente.cp" />
                                </div>
                                <div class="col-md-4">
                                    <label class="control-label">Colonia  {{($nacional) ? '' : '(opcional)'}}</label>
                                    <!--<select id="coloniaid" class="form-control" name="colonia" v-model="cliente.colonia" text-uppercase >

                                        
                                    </select>
                                    -->
                                    <input id="coloniaid" type="text" class="form-control" name="colonia" v-model="cliente.colonia"/>
                                    
                                </div>
                                @if($nacional)
                                    <div class="col-md-4">
                                        <label class="control-label">Delegacion</label>
                                        <input type="text" class="form-control delegacion" name="delagacion"
                                               v-model="cliente.delegacion"/>
                                    </div>
                                @endif
                                
                            </div>
                            <div class="row form-group">
                                <div class="col-md-4">
                                    <label class="control-label">Ciudad</label>
                                    <input type="text" class="form-control municipio" name="ciudad" v-model="cliente.ciudad"/>
                                </div>
                                <div class="col-md-4">
                                    <label class="control-label">Estado</label>
                                    <input type="text" class="form-control estado" name="estado" v-model="cliente.estado"/>
                                </div>
                                <div class="col-md-4">
                                    <label class="control-label">País</label>
                                    <input type="text" class="form-control" name="pais" v-model="cliente.pais"/>
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
                                    <input type="text" class="form-control" name="pagina_web"
                                           v-model="cliente.pagina_web"/>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-12">
                                    <label class="control-label">Datos Adicionales</label>
                                    <input type="text" class="form-control" name="adicionales"
                                           v-model="cliente.adicionales"/>
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
                            <h3 class="panel-title">Contactos del Cliente</h3>
                        </div>
                        <div class="panel-body">
                            <div class="row form-group">
                                <div class="col-md-6">
                                    <label class="control-label">Nombre<strong style="color: grey"> *</strong></label>
                                    <input type="text" class="form-control" name="nombre"
                                           v-model="contacto.nombre" required/>
                                </div>
                                <div class="col-md-6">
                                    <label class="control-label">Cargo<strong style="color: grey"> *</strong></label>
                                    <input type="text" class="form-control" name="cargo"
                                           v-model="contacto.cargo" required/>
                                </div>
                            </div>
                            <contacto-emails :emails="contacto.emails"
                                             :contacto_id="(contacto.id)?contacto.id:0"
                                             contacto_type="ClienteContacto">
                            </contacto-emails>
                            <contacto-telefonos :telefonos="contacto.telefonos"
                                                :contacto_id="(contacto.id)?contacto.id:0"
                                                contacto_type="ClienteContacto"
                                                :nacional="cliente.nacional">
                            </contacto-telefonos>
                            <div class="row form-group">
                                <div class="col-md-6">
                                    <label class="control-label">Fax</label>
                                    <input type="text" class="form-control" name="fax" v-model="contacto.fax">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 text-center">
                    @if($layout !=='iframe')
                        <a class="btn btn-default" href="{{route('clientes.index')}}" style="margin-right: 20px; background-color:#B3B3B3; color:#000;">
                            Regresar
                        </a>
                    @endif
                    <button type="submit" class="btn btn-dark" :disabled="cargando" style="background-color:#12160F; color:#B68911;">
                        <i class="fas fa-save"></i>
                        Guardar Cliente
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
                is_iframe: {{$layout==="iframe"?'true' : 'false'}},
                translations: translationsES,
                cliente: {
                    usuario_id: '',
                    vendedor_id: '',
                    tipo_id: '',
                    nombre: '',
                    rfc: '',
                    razon_social: '',
                    telefono: '',
                    email: '',
                    calle: '',
                    nexterior: '',
                    ninterior: '',
                    colonia: '',
                    delegacion: '',
                    cp: '',
                    ciudad: '',
                    estado: '',
                    pais: '{{ (($nacional)?"México":"") }}',
                    nacional: {{ (($nacional)?"true":"false") }},
                    pagina_web: '',
                    adicionales: '',
                    contactos: [],
                    userIds: []
                },
                contacto: {
                    tipo: 'cliente',
                    cliente_id: 0,
                    nombre: '',
                    cargo: '',
                    emails: [],
                    telefonos: [],
                    fax: ''
                },
                user: {
                    users: [],
                    userOptions: [
                        @foreach($usuarios as $id => $nombre)
                        {
                            id: "{{$id}}", text: "{{$nombre}}"
                        },
                        @endforeach
                    ],
                },
                cargando: false,
            },
            methods: {
                guardar() {
                    this.cargando = true;
                    this.cliente.contactos.push(this.contacto);
                    this.cliente.userIds = this.user.users;
                    axios.post('/clientes', this.cliente)
                        .then(({data}) => {
                            this.cargando = false;
                            swal({
                                title: "Cliente Guardado",
                                text: "",
                                type: "success"
                            }).then(() => {
                                if (this.is_iframe) {
                                    parent.postMessage({message: "OK", tipo: "cliente", object: data.cliente}, "*")
                                    window.location = "/clientes/crearNacional?layout=iframe";
                                } else {
                                    window.location = "/clientes/" + data.cliente.id + "/editar?contactos";
                                }
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
                    
                    if(data.asentamientos.length >= 1) {
                        
                        /*
                        $('#coloniaid').empty();

                        data['asentamientos'].forEach(function(colonia) {
                            $('#coloniaid').append('<option value="'+colonia+'">'+colonia+'</option>');
                        });
                        */
                        $('#coloniaid').val(data.asentamientos[0]);
                    }

                    if(data.estados.length >= 1) {
                        $('.estado').val(data.estados[0]);
                    }
                    if(data.municipios.length >= 1) {
                        $('.municipio').val(data.municipios[0]);
                        $('.delegacion').val(data.municipios[0]);
                    }
                });
            }
        });
    </script>
@stop
