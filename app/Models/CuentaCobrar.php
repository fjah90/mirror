<?php

namespace App\Models;

use App\Model;
use Carbon\Carbon;

class CuentaCobrar extends Model
{
    protected $table = 'cuentas_cobrar';

    protected $fillable = ['cliente_id','cotizacion_id','cliente','proyecto',
      'moneda','condiciones','total','facturado','pagado','pendiente','pagada',
      'comprobante_confirmacion','fecha_comprobante'
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

    public function setFechaComprobanteAttribute($value){
        list($dia, $mes, $ano) = explode('/', $value);
        $this->attributes['fecha_comprobante'] = "$ano-$mes-$dia";
    }

    public function getFechaComprobanteFormatedAttribute(){
        return Carbon::parse($this->fecha_comprobante)->format('d/m/Y');
    }
    public function cliente(){
      return $this->belongsTo('App\Models\Cliente', 'cliente_id', 'id');
    }

    public function cotizacion(){
      return $this->belongsTo('App\Models\ProspectoCotizacion', 'cotizacion_id', 'id');
    }

    public function facturas(){
      return $this->hasMany('App\Models\Factura', 'cuenta_id', 'id');
    }

}
