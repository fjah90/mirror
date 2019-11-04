<template>
  <div>
    <form @submit.prevent="agregar()">
      <div class="row form-group">
        <div class="col-md-12">
          <h4>Teléfonos</h4>
        </div>
        <div class="col-md-4">
          <label class="control-label">Tipo</label>
          <select class="form-control" v-model="telefono.tipo">
            <option value="Oficina">Oficina</option>
            <option value="Celular">Celular</option>
          </select>
        </div>
        <div class="col-md-4">
          <label class="control-label">Numero</label>
          <input type="tel" class="form-control" v-mask="['(###) ###-####','+#(###)###-####','+##(###)###-####']"
          v-model="telefono.telefono" required
          />
        </div>
        <div class="col-md-2">
          <label class="control-label">Extención</label>
          <input type="text" class="form-control" v-model="telefono.extencion" />
        </div>
        <div class="col-md-2 text-right" style="margin-top:25px;">
          <button class="btn btn-sm btn-primary" type="submit" title="Agregar" :disabled="cargando">
            <i class="fas fa-plus"></i>
          </button>
        </div>
      </div>
    </form>
    <div class="row" v-for="(telefono, index) in telefonos">
      <div class="col-md-4">{{telefono.tipo}}</div>
      <div class="col-md-4">{{telefono.telefono}}</div>
      <div class="col-md-2">{{telefono.extencion}}</div>
      <div class="col-md-2 text-right">
        <button class="btn btn-xs btn-success" type="button" title="Editar" @click="editar(telefono, index)">
          <i class="fas fa-edit"></i>
        </button>
        <button class="btn btn-xs btn-danger" type="button" title="Borrar" @click="borrar(telefono, index)" :disabled="cargando">
          <i class="fas fa-times"></i>
        </button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    'contacto_id': Number,
    'contacto_type': String,
    'telefonos': Array
  },
  data(){
    return {
      cargando: false,
      telefono: {tipo: "", telefono:"", extencion:""}
    }
  },
  methods: {
    agregar(){
      if(this.contacto_id == 0){
        this.telefonos.push(this.telefono);
        this.telefono = {tipo: "", telefono:"", extencion:""};
        return true;
      }

      if(this.telefono.id) this.actualizar();
      else this.guardar();
    },
    guardar(){
      this.cargando = true;
      axios.post('/telefonos', {contacto_id: this.contacto_id, contacto_type:this.contacto_type,
      tipo:this.telefono.tipo, telefono:this.telefono.telefono, extencion:this.telefono.extencion})
      .then(({data}) => {
        this.telefonos.push(data.telefono);
        this.telefono = {tipo: "", telefono:"", extencion:""};
        this.cargando = false;
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
    }, //guardarContacto
    actualizar(){
      this.cargando = true;
      axios.put('/telefonos/'+this.telefono.id, this.telefono)
      .then(({data}) => {
        this.telefonos.push(this.telefono);
        this.telefono = {tipo: "", telefono:"", extencion:""};
        this.cargando = false;
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
    },
    editar(telefono, index){
      this.telefono = telefono;
      this.telefonos.splice(index, 1);
    },
    borrar(telefono, index){
      if(telefono.id == undefined){
        this.telefonos.splice(index, 1);
        return true;
      }

      this.cargando = true;
      axios.delete('/telefonos/'+telefono.id, {})
      .then(({data}) => {
        this.telefonos.splice(index, 1);
        this.cargando = false;
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
    },
  }
}
</script>