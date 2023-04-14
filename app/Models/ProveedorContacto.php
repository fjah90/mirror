<?php

namespace App\Models;

use App\Model;
use Carbon\Carbon;

class ProveedorContacto extends Model
{
    protected $table = 'proveedores_contactos';

    protected $fillable = ['proveedor_id','nombre','cargo','fax'];

    protected $appends = ['tipo', 'telefono', 'email'];

    public function getTipoAttribute(){
      return 'proveedor';
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

    public function proveedor(){
      return $this->belongsTo('App\Models\Proveedor', 'proveedor_id', 'id');
    }

    public function emails(){
      return $this->morphMany('App\Models\ContactoEmail', 'contacto', 'contacto_type', 'contacto_id', 'id');
    }

    public function telefonos(){
      return $this->morphMany('App\Models\ContactoTelefono', 'contacto', 'contacto_type', 'contacto_id', 'id');
    }

}
