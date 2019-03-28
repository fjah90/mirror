<?php

namespace App\Models;

use App\Model;
use Carbon\Carbon;

class FacturaCuentaPagar extends Model
{
    protected $table = 'facturas_cuentas_pagar';

    protected $fillable = ['cuenta_id','documento','monto','vencimiento',
      'pendiente','pagado','pagada','pdf','xml'
    ];

    protected $casts = [
      'monto' => 'float',
      'pendiente' => 'float',
      'pagado' => 'float',
      'pagada' => 'boolean'
    ];

    protected $appends = ['vencimiento_formated'];

    /**
     * Convercion a formato de mysql. year-month-day
     * @param  string  $fecha en formato dia/mes/año
     * @return void
     */
    public function setVencimientoAttribute($fecha)
    {
        list($dia, $mes, $ano) = explode('/', $fecha);
        $this->attributes['vencimiento'] = "$ano-$mes-$dia";
    }

    /**
     * ---------------------------------------------------------------------------
     *                             Appends
     * ---------------------------------------------------------------------------
     */

     public function getVencimientoFormatedAttribute(){
       list($ano, $mes, $dia) = explode('-', $this->vencimiento);
       return "$dia/$mes/$ano";
     }


    /**
     * ---------------------------------------------------------------------------
     *                             Relationships
     * ---------------------------------------------------------------------------
     */

    public function cuenta(){
      return $this->belongsTo('App\Models\CuentaPagar', 'cuenta_id', 'id');
    }

    public function pagos(){
      return $this->hasMany('App\Models\PagoCuentaPagar', 'factura_id', 'id');
    }

}
