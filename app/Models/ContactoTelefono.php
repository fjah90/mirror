<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactoTelefono extends Model
{
    protected $table = 'contactos_telefonos';

    protected $fillable = ['contacto_id','contacto_type','telefono','extencion','tipo'];

    /**
     * ---------------------------------------------------------------------------
     *                             Relationships
     * ---------------------------------------------------------------------------
     */

    public function contacto(){
      return $this->morphTo();
    }

}
