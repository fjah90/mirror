<?php

namespace App\Models;

use App\Model;
use Carbon\Carbon;

class ClienteContacto extends Model
{
    protected $table = 'clientes_contactos';

    protected $fillable = ['cliente_id','nombre','cargo','telefono','email'];

    /**
     * ---------------------------------------------------------------------------
     *                             Relationships
     * ---------------------------------------------------------------------------
     */

    public function cliente(){
      return $this->belongsTo('App\Models\Cliente', 'cliente_id', 'id');
    }

}
