<?php

namespace App\Models;

use App\Model;
use Carbon\Carbon;

class Proveedor extends Model
{
    protected $table = 'proveedores';

    protected $fillable = ['empresa','telefono','email','identidad_fiscal',
      'identificacion_fiscal','banco','numero_cuenta','clave_interbancaria',
      'calle','numero','colonia','cp','ciudad','estado','moneda','dias_credito'
    ];

    protected $casts = [
      'dias_credito' => 'integer'
    ];

    protected $appends = ['direccion'];

    /**
     * ---------------------------------------------------------------------------
     *                             Relationships
     * ---------------------------------------------------------------------------
     */

    public function getDireccionAttribute(){
      return $this->calle." ".$this->numero." ".$this->colonia." ".$this->cp." ".$this->ciudad." ".$this->estado;
    }

    /**
     * ---------------------------------------------------------------------------
     *                             Relationships
     * ---------------------------------------------------------------------------
     */

    public function contactos(){
      return $this->hasMany('App\Models\ProveedorContacto', 'proveedor_id', 'id');
    }

}
