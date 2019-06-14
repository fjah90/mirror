<?php

namespace App\Models;

use App\Model;
use Carbon\Carbon;

class ProveedorContacto extends Model
{
    protected $table = 'proveedores_contactos';

    protected $fillable = ['proveedor_id','nombre','cargo','telefono','email','codigo_pais'];

    /**
     * ---------------------------------------------------------------------------
     *                             Relationships
     * ---------------------------------------------------------------------------
     */

    public function proveedor(){
      return $this->belongsTo('App\Models\Proveedor', 'cliente_id', 'id');
    }

}
