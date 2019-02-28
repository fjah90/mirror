<?php

namespace App\Models;

use App\Model;
use Carbon\Carbon;

class Pago extends Model
{
    protected $table = 'pagos';

    protected $fillable = ['factura_id','fecha','monto','referencia','comprobante'];

    protected $casts = ['monto' => 'float'];

    /**
     * Convercion a formato de mysql. year-month-day
     * @param  string  $fecha en formato dia/mes/año
     * @return void
     */
    public function setFechaAttribute($fecha)
    {
        list($dia, $mes, $ano) = explode('/', $fecha);
        $this->attributes['fecha'] = "$ano-$mes-$dia";
    }

    /**
     * ---------------------------------------------------------------------------
     *                             Relationships
     * ---------------------------------------------------------------------------
     */

}
