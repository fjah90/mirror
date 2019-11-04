<?php

namespace App\Models;

use App\Model;

class ClienteContacto extends Model
{
    protected $table = 'clientes_contactos';

    protected $fillable = ['cliente_id','nombre','cargo'];

    protected $appends = ['tipo','telefono','email'];

    public function getTipoAttribute(){
      return 'cliente';
    }

    public function getEmailAttribute(){
      return $this->emails->get(0, (object) ['email' => ""])->email;
    }

    public function getTelefonoAttribute(){
      return $this->telefonos->get(0, (object) ['telefono' => ""])->telefono;
    }

    /**
     * ---------------------------------------------------------------------------
     *                             Relationships
     * ---------------------------------------------------------------------------
     */

    public function cliente(){
      return $this->belongsTo('App\Models\Cliente', 'cliente_id', 'id');
    }

    public function emails(){
      return $this->morphMany('App\Models\ContactoEmail', 'contacto', 'contacto_type', 'contacto_id', 'id');
    }

    public function telefonos(){
      return $this->morphMany('App\Models\ContactoTelefono', 'contacto', 'contacto_type', 'contacto_id', 'id');
    }

}
