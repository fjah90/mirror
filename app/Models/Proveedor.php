<?php

namespace App\Models;

use App\Model;
use Carbon\Carbon;

class Proveedor extends Model
{
    protected $table = 'proveedores';

    protected $fillable = ['empresa','razon_social','telefono','email',
      'identidad_fiscal','identificacion_fiscal','banco','numero_cuenta',
      'clave_interbancaria','calle','numero','colonia','cp','ciudad','estado',
      'moneda','dias_credito','nacional','codigo_pais','pais','cuenta_intercorp',
      'limite_credito','swif','aba'
    ];

    protected $casts = [
      'dias_credito' => 'integer',
      'nacional' => 'boolean'
    ];

    protected $appends = ['direccion','internacional'];

    public function setNacionalAttribute($nacional)
    {
      $this->attributes['nacional'] = ($nacional)?1:0;
    }

    /**
     * ---------------------------------------------------------------------------
     *                             Agregated Attributes
     * ---------------------------------------------------------------------------
     */

    public function getDireccionAttribute(){
      return $this->calle." ".$this->numero." ".(($this->colonia)?$this->colonia." ":"")
        .$this->cp." ".$this->ciudad." ".$this->estado." ".$this->pais;
    }

    public function getInternacionalAttribute(){
      return !$this->nacional;
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
