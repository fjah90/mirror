<?php

namespace App\Models;

use App\Model;
use Carbon\Carbon;

class Cliente extends Model
{
    protected $table = 'clientes';

    protected $fillable = [
      'usuario_id','tipo_id','nombre','rfc','razon_social',
      'calle','ninterior','ninterior',
      'colonia', 'delegacion', 'cp','ciudad','estado', 'pais',
      'pagina_web','adicionales','nacional','usuario_nombre'
    ];

    protected $casts = [
      'nacional' => 'boolean'
    ];

    protected $appends = ['direccion','internacional'];

    public function setNacionalAttribute($nacional)
    {
      $this->attributes['nacional'] = ($nacional)?1:0;
    }

    /**
     * ---------------------------------------------------------------------------
     *                             Relationships
     * ---------------------------------------------------------------------------
     */

    public function getDireccionAttribute(){
      return 
      // $this->calle." ".$this->nexterior.(($this->ninterior)?" Int. ".$this->ninterior:"")." ".
      (($this->colonia)?$this->colonia." ":"").(($this->delegacion)?$this->delegacion." ":"")
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

    public function tipo(){
      return $this->belongsTo('App\Models\TipoCliente', 'tipo_id', 'id');
    }

    public function usuario(){
      return $this->belongsTo('App\User', 'usuario_id', 'id');
    }

    public function contactos(){
      return $this->hasMany('App\Models\ClienteContacto', 'cliente_id', 'id');
    }

}
