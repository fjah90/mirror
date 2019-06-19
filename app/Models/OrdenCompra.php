<?php

namespace App\Models;

use App\Model;
use Carbon\Carbon;

class OrdenCompra extends Model
{
    protected $table = 'ordenes_compra';

    const STATUS_PENDIENTE              = "Pendiente";
    const STATUS_POR_AUTORIZAR          = "Por Autorizar";
    const STATUS_APROBADA               = "Aprobada";
    const STATUS_RECHAZADA              = "Rechazada";
    const STATUS_CANCELADA              = "Cancelada";

    protected $fillable = ['cliente_id','proyecto_id','proveedor_id',
      'orden_proceso_id','cliente_nombre','proyecto_nombre','proveedor_empresa',
      'status','moneda','subtotal','iva','total',
      'motivo_rechazo','archivo'
    ];

    protected $casts = [
      'subtotal' => 'float',
      'iva' => 'float',
      'total' => 'float'
    ];

    /**
     * ---------------------------------------------------------------------------
     *                             Relationships
     * ---------------------------------------------------------------------------
     */

    public function cliente(){
      return $this->belongsTo('App\Models\Cliente', 'cliente_id', 'id');
    }

    public function proyecto(){
      return $this->belongsTo('App\Models\ProyectoAprobado', 'proyecto_id', 'id');
    }

    public function proveedor(){
      return $this->belongsTo('App\Models\Proveedor', 'proveedor_id', 'id');
    }

    public function entradas(){
      return $this->hasMany('App\Models\OrdenCompraEntrada', 'orden_id', 'id');
    }

    public function ordenProceso(){
      return $this->hasOne('App\Models\OrdenProceso', 'orden_compra_id', 'id');
    }

}