<?php

namespace App\Models;

use App\Model;
use Carbon\Carbon;

class CuentaPagar extends Model
{
    protected $table = 'cuentas_pagar';

    protected $fillable = ['proveedor_id','orden_compra_id','proveedor_empresa',
      'proyecto_nombre','moneda','dias_credito','total','facturado','pagado',
      'pendiente','pagada',
    ];

    protected $casts = [
      'total' => 'float',
      'facturado' => 'float',
      'pagado' => 'float',
      'pendiente' => 'float',
      'pagada' => 'boolean'
    ];

    /**
     * ---------------------------------------------------------------------------
     *                             Relationships
     * ---------------------------------------------------------------------------
     */

    public function proveedor(){
      return $this->belongsTo('App\Models\Proveedor', 'proveedor_id', 'id');
    }

    public function orden(){
      return $this->belongsTo('App\Models\OrdenCompra', 'orden_compra_id', 'id')->withTrashed();
    }

    public function facturas(){
      return $this->hasMany('App\Models\FacturaCuentaPagar', 'cuenta_id', 'id');
    }

}
