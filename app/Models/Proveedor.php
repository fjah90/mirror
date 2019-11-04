<?php

namespace App\Models;

use App\Model;
use Carbon\Carbon;

class Proveedor extends Model
{
    protected $table = 'proveedores';

    protected $fillable = [
      'empresa','numero_cliente',
      'razon_social','identidad_fiscal','identificacion_fiscal',
      'calle','nexterior',
      'colonia','delegacion','cp','ciudad','estado','pais',
      'pagina_web','adicionales',
      'moneda','limite_credito','dias_credito',
      'banco','numero_cuenta','clave_interbancaria','cuenta_intercorp','swift','aba',
      'banco_colonia','banco_delegacion','banco_ciudad','banco_estado','banco_zipcode','banco_pais',
      'nacional'
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
      return 
        // $this->calle." ".$this->nexterior." ".
        (($this->colonia)?$this->colonia." ":"")
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
