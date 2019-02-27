<?php

namespace App\Models;

use App\Model;
use Carbon\Carbon;

class CuentaCobrar extends Model
{
    protected $table = 'cuentas_cobrar';

    protected $fillable = ['cliente_id','cotizacion_id','cliente','proyecto',
      'condiciones','total','facturado','pagado','pendiente','comprobante_confirmacion'
    ];

    protected $casts = [
      'total' => 'float',
      'facturado' => 'float',
      'pagado' => 'float',
      'pendiente' => 'float'
    ];

    /**
     * ---------------------------------------------------------------------------
     *                             Relationships
     * ---------------------------------------------------------------------------
     */

    public function cliente(){
      return $this->belongsTo('App\Models\Cliente', 'cliente_id', 'id');
    }

    public function cotizacion(){
      return $this->belongsTo('App\Models\ProspectoCotizacion', 'cotizacion_id', 'id');
    }

}
