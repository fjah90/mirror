<?php

namespace App\Models;

use App\Model;
use Carbon\Carbon;

class Cliente extends Model
{
    protected $table = 'clientes';

    protected $fillable = ['tipo_id','nombre','telefono','email','rfc',
    'calle','numero','colonia','cp','ciudad','estado'];

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

    public function tipo(){
      return $this->belongsTo('App\Models\TipoCliente', 'tipo_id', 'id');
    }

    public function contactos(){
      return $this->hasMany('App\Models\ClienteContacto', 'cliente_id', 'id');
    }

}
